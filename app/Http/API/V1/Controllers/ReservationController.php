<?php

declare(strict_types=1);

namespace App\Http\API\V1\Controllers;

use App\AppConstants;
use App\Domains\Hotel\DTO\CreateReservationDTO;
use App\Domains\User\DTO\UserDTO;
use App\Domains\User\Model\User;
use App\Http\API\V1\Presenters\ReservationCreatedPresenter;
use App\Http\API\V1\Requests\CreateReservationRequest;
use App\UseCases\CreateReservationUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
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
        $userEmail = $user->email ?? $request->input('user.email');
        $userName = $user->name ?? $request->input('user.name');

        if (!$user && (!$userEmail || !$userName)) {
            throw new BadRequestException('Username or username is empty.');
        }

        $dto = new CreateReservationDTO(
            hotelId: (int)$request->input('hotel_id'),
            roomId: (int)$request->input('room_id'),
            dateFrom: (string)$request->input('date_from'),
            dateTo: (string)$request->input('date_to'),
            user: new UserDTO(id: $userId, email: $userEmail, name: $userName)
        );

        $reservation = $createReservation->execute($dto);
        $token = null;

        if (!$user) {
            $token = $reservation->user->createToken(
                name: AppConstants::HOTEL_API_V1,
                expiresAt: now()->addDays(AppConstants::TOKEN_EXPIRATION_DAYS)
            );
        }

        $presenter = new ReservationCreatedPresenter($reservation, $token);

        return response()->json($presenter->build());
    }
}
