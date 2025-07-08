<?php

declare(strict_types=1);

namespace App\Http\API\V1\Controllers;

use App\Domains\Hotel\DTO\RoomFilterDTO;
use App\Domains\Hotel\Services\HotelService;
use App\Domains\Hotel\Services\RoomService;
use App\Http\API\V1\Presenters\HotelPresenter;
use App\Http\API\V1\Presenters\HotelRoomsPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HotelController
{
    public function __construct(
        protected HotelService $hotelService,
        protected RoomService $roomService
    ) {
    }

    public function getInfo(int $id): JsonResponse
    {
        $hotel = $this->hotelService->findById($id);
        $presenter = new HotelPresenter($hotel);

        return response()->json($presenter->build());
    }

    public function getAvailableRooms(Request $request, int $hotelId): JsonResponse
    {
        $hotel = $this->hotelService->findById($hotelId);

        $dto = new RoomFilterDTO(
            dateFrom: (string)$request->get('dateFrom'),
            dateTo: (string)$request->get('dateTo'),
            initiator: $request->user(),
            hotelId: $hotel->id
        );

        $rooms = $this->roomService->getAvailable($dto);
        $presenter = new HotelRoomsPresenter($rooms);

        return response()->json($presenter->build());
    }
}
