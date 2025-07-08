<?php

declare(strict_types=1);

namespace App\Http\API\V1\Presenters;

use App\Domains\Hotel\Model\Room;
use Illuminate\Database\Eloquent\Collection;

class HotelRoomsPresenter
{
    public function __construct(protected Collection $items)
    {
    }

    public function build(): array
    {
        return $this->items->transform(function (Room $room) {
            return [
                'id' => $room->id,
                'title' => $room->title,
                'slug' => $room->slug,
                'check_in_time' => $room->check_in_time,
                'check_out_time' => $room->check_out_time,
            ];
        })->toArray();
    }
}
