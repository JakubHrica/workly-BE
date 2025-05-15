<?php
use Illuminate\Support\Facades\Route;
use AppUser\Profile\Http\Controllers\ProfileController;
use AppUser\Profile\Http\Middleware\ProfileMiddleware;

Route::prefix('/api/v1')->group(function () {
    Route::post('/register', [ProfileController::class, 'register']);
    Route::post('/login', [ProfileController::class, 'login']);   
    Route::middleware(ProfileMiddleware::class)->group(function () {
        Route::post('/logout', [ProfileController::class, 'logout']);
    });
});
