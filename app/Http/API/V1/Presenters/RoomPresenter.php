<?php

declare(strict_types=1);

namespace App\Http\API\V1\Presenters;

use App\Domains\Hotel\Model\Room;
use Illuminate\Database\Eloquent\Collection;

class RoomPresenter
{
    public function __construct(protected Room $room)
    {
    }

    public function build(): array
    {
        return [
            'id' => $this->room->id,
            'title' => $this->room->title,
            'slug' => $this->room->slug,
            'hotel_id' => $this->room->hotel_id,
            'hotel' => $this->room->isHotelLoaded() ? (new HotelPresenter($this->room->hotel))->build() : null,
            'check_in_time' => $this->room->check_in_time,
            'check_out_time' => $this->room->check_out_time,
        ];
    }
}
