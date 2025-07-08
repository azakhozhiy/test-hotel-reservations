<?php

use App\Http\API\V1\Controllers\AuthController;
use App\Http\API\V1\Controllers\HotelController;
use App\Http\API\V1\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::post('/auth', [AuthController::class, 'auth']);

    Route::prefix('hotels')->group(function () {
        Route::get('/list', [HotelController::class, 'getList']);
        Route::prefix('{id}')->group(function () {
            Route::get('/', [HotelController::class, 'getInfo']);
            Route::get('/rooms', [HotelController::class, 'getRooms']);
        });
    });

    Route::prefix('reservations')->group(function () {
        Route::post('/', [ReservationController::class, 'create']);
    });
});

