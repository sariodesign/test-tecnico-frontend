<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/settings', [SettingsController::class, 'index']);
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/categories', [EventController::class, 'categories']);
Route::get('/events/{slug}', [EventController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'show']);

    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/events/{event}/favorite', [FavoriteController::class, 'store']);
    Route::delete('/events/{event}/favorite', [FavoriteController::class, 'destroy']);
});
