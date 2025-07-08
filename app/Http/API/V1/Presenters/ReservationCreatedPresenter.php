<?php

declare(strict_types=1);

namespace App\Http\API\V1\Presenters;

use App\Domains\Hotel\Model\Reservation;
use Laravel\Sanctum\NewAccessToken;

class ReservationCreatedPresenter
{
    public function __construct(protected Reservation $reservation, protected ?NewAccessToken $token = null)
    {
    }

    public function build(): array
    {
        return [
            'data' => [
                'token' => $this->token ? (new TokenPresenter($this->token))->build() : null,
                'reservation' => (new ReservationPresenter($this->reservation))->build(),
            ],
        ];
    }
}
