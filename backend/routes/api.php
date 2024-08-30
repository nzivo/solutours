<?php

use App\Http\Controllers\API\v1\DestinationController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginController::class, 'index']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::apiResource('destinations', DestinationController::class);

