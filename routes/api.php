<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TourController;
use App\Http\Middleware\AdminRoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$sanctum = 'auth:sanctum';

Route::prefix('auth/')->group(function () use ($sanctum) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware($sanctum);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

Route::middleware([$sanctum, AdminRoleMiddleware::class])->group(function () {

    Route::apiResources([
        // 'user' => UserController::class,
        'tour' => TourController::class,
        'destination' => DestinationController::class,
        'booking' => BookingController::class,
        'ticket' => TicketController::class
    ]);
});
