<?php
use Illuminate\Support\Facades\Route;
use AppUser\Profile\Http\Controllers\ProfileController;
use AppUser\Profile\Http\Middleware\ProfileMiddleware;

Route::group([
    'prefix' => '/api/v1'
], function () {
    Route::post('/register', [ProfileController::class, 'register']);
    Route::post('/login', [ProfileController::class, 'login']); 

    Route::group(['middleware' => ProfileMiddleware::class], function () {
        Route::post('/logout', [ProfileController::class, 'logout']);
    });
});