<?php

declare(strict_types=1);

namespace App\Domains\Hotel\Enum;

enum ReservationStatusEnum: int
{
    case WAITING_FOR_PAYMENT = 1;
    case PAID = 2;
    case CANCELLED = 3;
}
