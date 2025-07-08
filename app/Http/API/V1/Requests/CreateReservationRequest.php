<?php

declare(strict_types=1);

namespace App\Http\API\V1\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReservationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'hotel_id' => ['required', 'int'],
            'room_id' => ['required', 'int'],
            'date_from' => ['required', 'date', 'after_or_equal:today'],
            'date_to' => ['required', 'date', 'after:date_from'],
        ];
    }
}
