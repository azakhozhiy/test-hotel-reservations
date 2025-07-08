<?php

declare(strict_types=1);

namespace App\Http\API\V1\Presenters;

use Laravel\Sanctum\NewAccessToken;

class TokenPresenter
{
    public function __construct(protected NewAccessToken $token)
    {
    }

    public function build(): array
    {
        return [
            'access_token' => $this->token->plainTextToken,
            'expires_at' => $this->token->accessToken->expires_at,
            'abilities' => $this->token->accessToken->abilities,
            'token_type' => 'bearer',
        ];
    }
}
