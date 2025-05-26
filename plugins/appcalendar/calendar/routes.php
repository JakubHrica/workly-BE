<?php
use Illuminate\Support\Facades\Route;
use AppCalendar\Calendar\Http\Controllers\CalendarController;
use AppUser\Profile\Http\Middleware\ProfileMiddleware;

Route::group([
    'prefix' => 'api/v1',
    'middleware' => ProfileMiddleware::class
], function () {
    Route::get('/get_calendar', [CalendarController::class, 'getCalendar']);
});