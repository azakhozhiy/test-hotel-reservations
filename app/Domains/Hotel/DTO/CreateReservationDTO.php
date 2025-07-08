<?php

declare(strict_types=1);

namespace App\Domains\Hotel\DTO;

use App\Domains\User\DTO\UserDTO;

class CreateReservationDTO
{
    public function __construct(
        protected int $hotelId,
        protected int $roomId,
        protected string $dateFrom,
        protected string $dateTo,
        protected UserDTO $user,
    ) {
    }

    public function getHotelId(): int
    {
        return $this->hotelId;
    }

    public function getRoomId(): int
    {
        return $this->roomId;
    }

    public function getDateFrom(): string
    {
        return $this->dateFrom;
    }

    public function getDateTo(): string
    {
        return $this->dateTo;
    }

    public function getUser(): UserDTO
    {
        return $this->user;
    }
}
