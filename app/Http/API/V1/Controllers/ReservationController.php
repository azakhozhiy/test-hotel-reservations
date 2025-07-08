<?php

declare(strict_types=1);

namespace App\Http\API\V1\Controllers;

use App\Domains\Hotel\DTO\CreateReservationDTO;
use App\Domains\User\DTO\UserDTO;
use App\Domains\User\Model\User;
use App\Http\API\V1\Presenters\ReservationPresenter;
use App\Http\API\V1\Requests\CreateReservationRequest;
use App\UseCases\CreateReservationUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Throwable;

class ReservationController extends Controller
{
    /**
     * @throws Throwable
     */
    public function create(CreateReservationRequest $request, CreateReservationUseCase $createReservation): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user() ?: null;
        $userId = $user?->id;
        $userEmail = $user->email ?? $request->input('email');

        $dto = new CreateReservationDTO(
            hotelId: $request->input('hotelId'),
            roomId: $request->input('roomId'),
            dateFrom: $request->input('dateFrom'),
            dateTo: $request->input('dateTo'),
            user: new UserDTO(id: $userId, email: $userEmail)
        );

        $reservation = $createReservation->execute($dto);
        $presenter = new ReservationPresenter($reservation);

        return response()->json($presenter->build());
    }
}
