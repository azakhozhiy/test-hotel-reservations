<?php

declare(strict_types=1);

namespace App\Http\API\V1\Presenters;

use App\Domains\Hotel\Model\Reservation;

class ReservationPresenter
{
    public function __construct(protected Reservation $reservation)
    {
    }

    public function build(): array{
        return [
            'id' => $this->reservation->id,
            'room' => $this->reservation->rela
        ];
    }
}
