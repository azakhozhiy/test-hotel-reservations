<?php

declare(strict_types=1);

namespace App\Domains\Hotel\Repository;

use App\Domains\Hotel\Model\Hotel;
use Illuminate\Database\Eloquent\Builder;

class HotelRepository
{
    public function query(): Builder
    {
        return Hotel::query();
    }

    public function findById(int $id, bool $lockForUpdate = false): Hotel
    {
        $query = $this->query()->whereKey($id);

        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        return $query->firstOrFail();
    }
}
