<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Domains\Hotel\DTO\CreateReservationDTO;
use App\Domains\Hotel\DTO\RoomFilterDTO;
use App\Domains\Hotel\Enum\ReservationStatusEnum;
use App\Domains\Hotel\Model\Reservation;
use App\Domains\Hotel\Services\HotelService;
use App\Domains\Hotel\Services\RoomService;
use App\Domains\User\DTO\UserDTO;
use App\Domains\User\Model\User;
use App\Domains\User\Repository\UserRepository;
use App\Jobs\RoomReserved;
use App\Services\RoomReserveLocker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;
use Throwable;

class CreateReservationUseCase
{
    public function __construct(
        protected HotelService $hotelService,
        protected RoomService $roomService,
        protected UserRepository $userRepository,
        protected LoggerInterface $logger,
        protected RoomReserveLocker $roomReserveLocker
    ) {
    }

    private function findOrCreateUser(UserDTO $dto): User
    {
        if ($this->userRepository->checkExists(User::FIELD_EMAIL, $dto->getEmail())) {
            return $this->userRepository->findByEmail($dto->getEmail());
        }

        $user = new User();
        $user->name = $dto->getName();
        $user->email = $dto->getEmail();
        $user->password = Str::random(5);
        $user->save();

        return $user;
    }

    /**
     * @throws Throwable
     */
    public function execute(CreateReservationDTO $dto): Reservation
    {
        return $this->roomReserveLocker->lock(
            $dto->getRoomId(),
            $dto->getDateFrom(),
            $dto->getDateTo(),
            fn() => $this->createReservation($dto)
        );
    }

    /**
     * @throws Throwable
     */
    public function createReservation(CreateReservationDTO $dto): Reservation
    {
        $hotel = $this->hotelService->findById($dto->getHotelId());

        $roomFilter = new RoomFilterDTO(
            dateFrom: $dto->getDateFrom(),
            dateTo: $dto->getDateTo(),
            initiator: $dto->getUser()->getAuthUser(),
            hotelId: $hotel->getKey(),
        );

        DB::beginTransaction();

        try {
            $user = $dto->getUser()->getAuthUser() ?: $this->findOrCreateUser($dto->getUser());

            $room = $this->roomService->findAvailableRoom(
                filterDTO: $roomFilter,
                roomId: $dto->getRoomId(),
                lockForUpdate: true
            );

            $room->setHotelRelation($hotel);

            $dateFrom = Carbon::parse($dto->getDateFrom());
            $dateTo = Carbon::parse($dto->getDateTo());
            $countDays = $dateFrom->diffInDays($dateTo);

            $reservation = new Reservation();
            $reservation->room()->associate($room);
            $reservation->user()->associate($user);
            $reservation->count_days = $countDays;

            $reservation->check_in_at = $dateTo
                ->setHours($room->getCheckInHours())
                ->setMinutes($room->getCheckInMinutes());

            $reservation->check_out_at = $dateFrom
                ->setHours($room->getCheckOutHours())
                ->setMinutes($room->getCheckOutMinutes());

            $reservation->date_from = $dateFrom;
            $reservation->date_to = $dateTo;
            $reservation->status = ReservationStatusEnum::WAITING_FOR_PAYMENT;
            $reservation->save();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logger->error("Creating reservation ended with error: {$e->getMessage()}.");

            throw $e;
        }

        dispatch(new RoomReserved($reservation));

        return $reservation;
    }
}
