<?php

use App\Http\Controllers\DutyController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/duties', [DutyController::class, 'apiCalendar']);
});
