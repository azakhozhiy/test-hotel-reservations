<?php

namespace App\Providers;

use App\Domains\User\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Auth::viaRequest('api', static function (Request $request) {
            $bearerToken = $request->bearerToken();

            if (!$bearerToken) {
                return null;
            }

            $accessToken = PersonalAccessToken::findToken($bearerToken);

            if ($accessToken) {
                $accessToken->load('tokenable');

                return $accessToken->tokenable;
            }

            return null;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
