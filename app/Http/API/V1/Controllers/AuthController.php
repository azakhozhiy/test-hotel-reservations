<?php

declare(strict_types=1);

namespace App\Http\API\V1\Controllers;

use App\AppConstants;
use App\Domains\User\Model\User;
use App\Http\API\V1\Presenters\TokenPresenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class AuthController
{
    public const string API_NAME = 'reservation-api';

    /**
     * @throws ValidationException
     */
    public function auth(Request $request): JsonResponse
    {
        $email = $request->input('email');
        $password = $request->input('password');

        try {
            $user = User::query()->where('email', $email)->firstOrFail();
        } catch (Throwable) {
            throw new AccessDeniedHttpException('The provided credentials are incorrect.');
        }

        if (!Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken(
            name: AppConstants::HOTEL_API_V1,
            expiresAt: now()->add(AppConstants::TOKEN_EXPIRATION_DAYS)
        );

        $presenter = new TokenPresenter($token);

        return response()->json($presenter->build());
    }
}
