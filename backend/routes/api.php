<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\BookingsController;
use App\Http\Controllers\API\v1\DestinationController;
use App\Http\Controllers\API\v1\TicketsController;
use App\Http\Controllers\API\v1\TourController;
use App\Http\Controllers\API\v1\UserController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

Route::group(['middleware' => ['auth:api'],
    'prefix' => 'v1'], function () {
    // Tour booking
    Route::post('/book-tour', [BookingsController::class, 'bookTour']);

    // Ticket generation and viewing
    Route::post('/generate-ticket', [TicketsController::class, 'generateTicket']);
    Route::get('/view-ticket/{booking_id}', [TicketsController::class, 'viewTicket']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'
], function ($router) {

    Route::apiResource('destinations', DestinationController::class);

    Route::apiResource('tours', TourController::class);

    Route::apiResource('users', UserController::class);
});

