<?php

use Illuminate\Support\Facades\Route;
use Jakub\Workly\Http\Controllers\ProfileController;
use Jakub\Workly\Http\Controllers\EventController;

Route::post('/register', [ProfileController::class, 'register']);
Route::post('/login', [ProfileController::class, 'login']);

Route::get('/get-events', [EventController::class, 'getEvents']);
Route::post('/new-event', [EventController::class, 'newEvent']);
Route::post('/update-event', [EventController::class, 'updateEvent']);
Route::post('/delete-event', [EventController::class, 'deleteEvent']);