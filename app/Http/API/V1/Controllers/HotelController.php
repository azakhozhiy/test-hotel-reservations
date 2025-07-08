<?php

declare(strict_types=1);

namespace App\Http\API\V1\Controllers;

use App\Domains\Hotel\DTO\RoomFilterDTO;
use App\Domains\Hotel\Services\HotelService;
use App\Domains\Hotel\Services\RoomService;
use App\Http\API\V1\Presenters\HotelPresenter;
use App\Http\API\V1\Presenters\RoomsPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HotelController
{
    public function __construct(
        protected HotelService $hotelService,
        protected RoomService $roomService
    ) {
    }


    public function getList(Request $request): JsonResponse
    {
        $paginator = $this->hotelService->getPaginated($request->get('perPage', 20));

        return response()->json($paginator);
    }

    public function getInfo(int $id): JsonResponse
    {
        $hotel = $this->hotelService->findById($id);
        $presenter = new HotelPresenter($hotel);

        return response()->json($presenter->build());
    }

    public function getRooms(Request $request, int $hotelId): JsonResponse
    {
        $hotel = $this->hotelService->findById($hotelId);

        $dateFrom = $request->get('dateFrom');
        $dateTo = $request->get('dateTo');

        if ($dateFrom === null || $dateTo === null) {
            $dateFrom = now()->toDateString();
            $dateTo = now()->addDays(7)->toDateString();
        }

        $filterDTO = new RoomFilterDTO(
            dateFrom: $dateFrom,
            dateTo: $dateTo,
            initiator: $request->user(),
            hotelId: $hotel->id
        );

        $rooms = $this->roomService->getAvailable($filterDTO);

        $presenter = new RoomsPresenter($rooms);

        return response()->json($presenter->build());
    }
}
