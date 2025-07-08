<?php

declare(strict_types=1);

namespace App\Http\API\V1\Presenters;

use App\Domains\Hotel\Model\Room;
use Illuminate\Database\Eloquent\Collection;

class RoomsPresenter
{
    protected string $wrapperKey = 'data';

    public function setWrapperKey(string $key): static
    {
        $this->wrapperKey = $key;

        return $this;
    }

    public function __construct(protected Collection $items)
    {
    }

    public function build(): array
    {
        $data = $this->items->transform(function (Room $room) {
            return [
                'id' => $room->id,
                'title' => $room->title,
                'slug' => $room->slug,
                'hotel_id' => $room->hotel_id,
                'check_in_time' => $room->check_in_time,
                'check_out_time' => $room->check_out_time,
            ];
        })->toArray();

        if ($this->wrapperKey) {
            $data = [$this->wrapperKey => $data];
        }

        return $data;
    }
}
