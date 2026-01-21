<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\GameMatchController;
use App\Http\Controllers\MoveController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);

    Route::get('/games', [GameController::class, 'index']);

    Route::get('/matches', [GameMatchController::class, 'index']);
    Route::post('/matches', [GameMatchController::class, 'store']);
    Route::get('/matches/{match}', [GameMatchController::class, 'show']);

    Route::post('/matches/{match}/moves', [MoveController::class, 'store']);
});