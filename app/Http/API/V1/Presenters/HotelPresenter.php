<?php

declare(strict_types=1);

namespace App\Http\API\V1\Presenters;

use App\Domains\Hotel\Model\Hotel;

class HotelPresenter
{
    public function __construct(protected Hotel $hotel)
    {
    }

    public function build(): array
    {
        return [
            'id' => $this->hotel->id,
            'title' => $this->hotel->title,
            'slug' => $this->hotel->slug,
        ];
    }
}
