<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Hotel\Model\Hotel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        for ($i = 0; $i <= 9; $i++) {
            $data[$i][Hotel::FIELD_TITLE] = 'Hotel #'.$i + 1;
            $data[$i][Hotel::FIELD_SLUG] = Str::slug('Hotel #'.$i + 1);
        }

        Hotel::query()->insert($data);
    }
}
