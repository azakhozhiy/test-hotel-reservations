<?php

declare(strict_types=1);

namespace App\Http\API\V1\Presenters;

use App\Domains\Hotel\Model\Reservation;

class ReservationPresenter
{
    public function __construct(protected Reservation $reservation)
    {
    }

    public function build(): array
    {
        return [
            'id' => $this->reservation->id,
            'count_days' => $this->reservation->count_days,
            'status' => $this->reservation->status->name,
            'room_id' => $this->reservation->room_id,
            'room' => $this->reservation->isRoomLoaded() ?
                (new RoomPresenter($this->reservation->room))->build()
                : null,
            'check_in_at' => $this->reservation->check_in_at,
            'check_out_at' => $this->reservation->check_out_at,
            'created_at' => $this->reservation->created_at->toIso8601ZuluString(),
            'updated_at' => $this->reservation->updated_at?->toIso8601ZuluString(),
        ];
    }
}
