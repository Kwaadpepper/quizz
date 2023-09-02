<?php

use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('ajax.')->group(function () {
    // * USERS
    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'getLogged'])->name('getLogged');
    });
    // * TEAMS
    Route::prefix('/users')->name('users.')->group(function () {
        Route::resource('teams', TeamController::class);
    });
});
