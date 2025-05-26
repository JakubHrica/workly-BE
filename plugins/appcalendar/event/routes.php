<?php
use Illuminate\Support\Facades\Route;
use AppCalendar\Event\Http\Controllers\EventController;
use AppUser\Profile\Http\Middleware\ProfileMiddleware;

Route::group([
    'prefix' => 'api/v1',
    'middleware' => ProfileMiddleware::class
], function () {
    Route::post('/new_event', [EventController::class, 'newEvent']);
    Route::post('/update_event/{eventId}', [EventController::class, 'updateEvent']);
    Route::delete('/delete_event/{eventId}', [EventController::class, 'deleteEvent']);
});