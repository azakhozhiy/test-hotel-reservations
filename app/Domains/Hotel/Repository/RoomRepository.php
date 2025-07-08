<?php

declare(strict_types=1);

namespace App\Domains\Hotel\Repository;

use App\Domains\Hotel\Enum\ReservationStatusEnum;
use App\Domains\Hotel\Model\Reservation;
use App\Domains\Hotel\Model\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class RoomRepository
{
    public function query(): Builder
    {
        return Room::query();
    }

    public function getQueryForCheckAvailability(string $dateFrom, string $dateTo): Builder
    {
        $statuses = [
            ReservationStatusEnum::WAITING_FOR_PAYMENT,
            ReservationStatusEnum::PAID,
        ];

        return $this->query()
            ->whereDoesntHave('reservations', function (Builder $q) use ($dateFrom, $dateTo, $statuses) {
                $q->where(function (Builder $query) use ($dateFrom, $dateTo) {
                    $query->whereDate(Reservation::FIELD_DATE_FROM, '<=', $dateTo)
                        ->whereDate(Reservation::FIELD_DATE_TO, '>=', $dateFrom);
                })->whereIn(Reservation::FIELD_STATUS, $statuses);
            });
    }
}
