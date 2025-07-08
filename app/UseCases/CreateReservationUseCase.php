<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Domains\Hotel\DTO\CreateReservationDTO;
use App\Domains\Hotel\DTO\RoomFilterDTO;
use App\Domains\Hotel\Enum\ReservationStatusEnum;
use App\Domains\Hotel\Model\Reservation;
use App\Domains\Hotel\Repository\UserRepository;
use App\Domains\Hotel\Services\HotelService;
use App\Domains\Hotel\Services\RoomService;
use App\Domains\User\DTO\UserDTO;
use App\Domains\User\Model\User;
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
        protected LoggerInterface $logger
    ) {
    }

    private function findOrCreateUser(UserDTO $dto): User
    {
        if ($this->userRepository->checkExists(User::FIELD_EMAIL, $dto->getEmail())) {
            return $this->userRepository->findByEmail($dto->getEmail());
        }

        $user = new User();
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
        $hotel = $this->hotelService->findById($dto->getHotelId());

        $roomFilter = new RoomFilterDTO(
            dateFrom: $dto->getDateFrom(),
            dateTo: $dto->getDateTo(),
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

            $reservation = new Reservation();
            $reservation->room()->associate($room);
            $reservation->user()->associate($user);
            $reservation->check_out_at = $room->check_out_time;
            $reservation->check_in_at = $room->check_in_time;
            $reservation->date_from = Carbon::parse($dto->getDateFrom());
            $reservation->date_to = Carbon::parse($dto->getDateTo());
            $reservation->status = ReservationStatusEnum::WAITING;
            $reservation->save();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logger->error("Creating reservation ended with error: {$e->getMessage()}.");

            throw $e;
        }

        return $reservation;
    }
}
