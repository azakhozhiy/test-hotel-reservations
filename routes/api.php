<?php

use App\Http\API\V1\Controllers\HotelController;
use App\Http\API\V1\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::prefix('hotels')->group(function () {
        Route::prefix('{id}')->group(function () {
            Route::get('/', [HotelController::class, 'getInfo']);
            Route::get('/rooms', [HotelController::class, 'getAvailableRooms']);
        });
    });

    Route::prefix('reservations')->group(function () {
        Route::post('/', [ReservationController::class, 'create']);
    });
});

