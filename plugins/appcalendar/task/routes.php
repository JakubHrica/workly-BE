<?php
use Illuminate\Support\Facades\Route;
use AppCalendar\Task\Http\Controllers\TaskController;
use AppUser\Profile\Http\Middleware\ProfileMiddleware;

Route::group([
    'prefix' => '/api/v1',
    'middleware' => ProfileMiddleware::class
], function () {
    Route::post('/new_task', [TaskController::class, 'newTask']);
        Route::post('/update_task/{taskId}', [TaskController::class, 'updateTask']);
        Route::delete('/delete_task/{taskId}', [TaskController::class, 'deleteTask']);
});