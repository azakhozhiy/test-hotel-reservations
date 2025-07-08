<?php

declare(strict_types=1);

namespace App\Domains\Hotel\Services;

use App\Domains\Hotel\Model\Hotel;
use App\Domains\Hotel\Repository\HotelRepository;

class HotelService
{
    public function __construct(protected HotelRepository $hotelRepository)
    {
    }

    public function findById(int $id, bool $lockForUpdate = false): Hotel
    {
        return $this->hotelRepository->findById($id, $lockForUpdate);
    }
}
