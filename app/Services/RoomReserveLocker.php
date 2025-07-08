<?php

declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Closure;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;

class RoomReserveLocker
{
    /**
     * @throws LockTimeoutException
     */
    public function lock(int $roomId, string $from, string $to, Closure $callback)
    {
        $dateFrom = Carbon::parse($from);
        $dateTo = Carbon::parse($to)->subDay();

        $period = CarbonPeriod::create($dateFrom, $dateTo);
        $keys = [];

        foreach ($period as $date) {
            $keys[] = "room:$roomId:{$date->format('Ymd')}:lock";
        }

        return $this->acquireLocks($keys, $callback);
    }

    /**
     * @throws LockTimeoutException
     */
    private function acquireLocks(array $keys, Closure $callback): mixed
    {
        if (empty($keys)) {
            return $callback();
        }

        $key = array_shift($keys);

        return Cache::lock($key, 5)->block(10, function () use ($keys, $callback) {
            return $this->acquireLocks($keys, $callback);
        });
    }
}
