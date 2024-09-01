<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\BookingsController;
use App\Http\Controllers\API\v1\DestinationController;
use App\Http\Controllers\API\v1\TicketsController;
use App\Http\Controllers\API\v1\TourController;
use App\Http\Controllers\API\v1\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth:api'],
    'prefix' => 'v1'], function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');

    // Tour booking
    Route::get('/bookings', [BookingsController::class, 'getBookings']);
    Route::get('/my-bookings', [BookingsController::class, 'getMyBookings']);
    Route::post('/book-tour', [BookingsController::class, 'bookTour']);
    Route::post('/confirm-booking/{booking_id}', [BookingsController::class, 'confirmBooking']);

    // Ticket generation and viewing
    Route::get('/get-all-tickets',[TicketsController::class, 'getAllTickets']);
    Route::get('/get-my-tickets',[TicketsController::class, 'getMyTickets']);
    Route::post('/generate-ticket', [TicketsController::class, 'generateTicket']);
    Route::get('/view-ticket/{booking_id}', [TicketsController::class, 'viewTicket']);

    // CRUD destinations
    Route::post('destinations', [DestinationController::class, 'store']);
    Route::put('destinations/{destination}', [DestinationController::class, 'update']);
    Route::delete('destinations/{destination}', [DestinationController::class, 'destroy']);

    // CRUD Tours
    Route::post('tours', [TourController::class, 'store']);
    Route::put('tours/{tour}', [TourController::class, 'update']);
    Route::delete('tours/{tour}', [TourController::class, 'destroy']);

    // CRUD users
    Route::apiResource('users', UserController::class);

});

// Unauthenticated endpoints
Route::group([
    'prefix' => 'v1'
], function ($router) {

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('destinations', [DestinationController::class, 'index']);
    Route::get('destinations/{destination}', [DestinationController::class, 'show']);

    Route::get('tours', [TourController::class,'index']);
    Route::get('tours/{tour}', [TourController::class, 'show']);
});

