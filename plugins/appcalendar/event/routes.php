<?php
use Illuminate\Support\Facades\Route;
use AppCalendar\Event\Http\Controllers\EventController;
use AppUser\Profile\Http\Middleware\ProfileMiddleware;

Route::prefix('/api/v1')->group(function () {
    Route::middleware(ProfileMiddleware::class)->group(function () {
        Route::get('/get_events', [EventController::class, 'getEvents']);
        Route::post('/new_event', [EventController::class, 'newEvent']);
        Route::post('/update_event/{eventId}', [EventController::class, 'updateEvent']);
        Route::delete('/delete_event/{eventId}', [EventController::class, 'deleteEvent']);
    });
});
