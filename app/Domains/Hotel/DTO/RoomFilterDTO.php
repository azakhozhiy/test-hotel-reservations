<?php

declare(strict_types=1);

namespace App\Domains\Hotel\DTO;

use App\Domains\User\Model\User;

class RoomFilterDTO
{
    public function __construct(
        protected string $dateFrom,
        protected string $dateTo,
        protected ?User $initiator = null,
        protected int $hotelId,
        protected array $additional = []
    ) {
    }

    public function getInitiator(): ?User
    {
        return $this->initiator;
    }

    public function getHotelId(): int
    {
        return $this->hotelId;
    }

    public function getDateFrom(): string
    {
        return $this->dateFrom;
    }

    public function getDateTo(): string
    {
        return $this->dateTo;
    }

    public function getAdditional(): array
    {
        return $this->additional;
    }
}
