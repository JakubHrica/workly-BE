<?php

use Illuminate\Support\Facades\Route;
use Jakub\Workly\Http\Controllers\ProfileController;

Route::post('/register', [ProfileController::class, 'register']);
Route::post('/login', [ProfileController::class, 'login']);