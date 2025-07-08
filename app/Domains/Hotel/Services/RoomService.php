<?php

declare(strict_types=1);

namespace App\Domains\Hotel\Services;

use App\Domains\Hotel\DTO\RoomFilterDTO;
use App\Domains\Hotel\Model\Hotel;
use App\Domains\Hotel\Model\Room;
use App\Domains\Hotel\Repository\RoomRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class RoomService
{
    public function __construct(protected RoomRepository $roomRepository)
    {
    }

    public function getAvailable(RoomFilterDTO $filterDTO): Collection
    {
        return $this->roomRepository->getQueryForCheckAvailability($filterDTO->getDateFrom(), $filterDTO->getDateTo())
            ->where(Room::FIELD_HOTEL_ID, $filterDTO->getHotelId())
            ->orderBy('id', 'desc')
            ->get();
    }

    public function findAvailableRoom(RoomFilterDTO $filterDTO, int $roomId, bool $lockForUpdate = false): Room
    {
        return $this->roomRepository->getQueryForCheckAvailability($filterDTO->getDateFrom(), $filterDTO->getDateTo())
            ->whereKey($roomId)
            ->where(Room::FIELD_HOTEL_ID, $filterDTO->getHotelId())
            ->when($lockForUpdate, fn(Builder $q) => $q->lockForUpdate())
            ->firstOrFail();
    }
}
