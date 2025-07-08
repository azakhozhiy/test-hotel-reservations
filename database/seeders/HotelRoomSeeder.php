<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Hotel\Model\Hotel;
use App\Domains\Hotel\Model\Room;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HotelRoomSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = [
            'Single Bed Room',
            'Double Bed Room',
            'Twin Bed Room',
            'Deluxe Room',
            'Suite Room',
            'Executive Suite',
            'Presidential Suite',
            'Studio Room',
            'Family Room',
            'Bunk Bed Room',
            'Penthouse',
            'Accessible Room',
        ];

        $i = 0;
        /** @var Collection<Hotel> $hotels */
        $hotels = Hotel::query()->get();
        $data = [];

        foreach ($hotels as $hotel) {
            foreach ($sizes as $size) {
                $data[$i] = [
                    Room::FIELD_TITLE => $size,
                    Room::FIELD_SLUG => Str::slug($size),
                    Room::FIELD_HOTEL_ID => $hotel->getKey(),
                    Room::FIELD_CHECK_IN_TIME => '15:00',
                    Room::FIELD_CHECK_OUT_TIME => '11:00',
                    Room::CREATED_AT => now(),
                ];
                ++$i;
            }
        }

        Room::query()->insert($data);
    }
}
