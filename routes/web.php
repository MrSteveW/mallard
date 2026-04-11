<?php

use App\Http\Controllers\CalendarNoteController;
use App\Http\Controllers\DutyController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ShiftPatternController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Resources\CalendarNoteResource;
use App\Models\CalendarNote;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return Inertia::render('welcome', [
        'status' => session('status'),
        'canResetPassword' => Route::has('password.request'),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard', [
            'calendarNotes' => CalendarNoteResource::collection(CalendarNote::orderBy('date')->get()),
        ]);
    })->name('dashboard');

});

// Admin only auth
Route::middleware(['auth', 'can:viewAny,'.User::class])->group(function () {

    Route::resource('users', UserController::class)->except(['show']);
    Route::get('users/{user}', function () {
        return redirect()->route('users.index');
    });

    Route::resource('tasks', TaskController::class);
    Route::resource('grades', GradeController::class);
    Route::resource('shiftpatterns', ShiftPatternController::class)
        ->parameters(['shiftpatterns' => 'user']);
    Route::post('duties/generate', [DutyController::class, 'generate']);
    Route::patch('duties/{duty}/cancel', [DutyController::class, 'cancel']);
    Route::resource('duties', DutyController::class);
    Route::resource('calendar-notes', CalendarNoteController::class)
        ->only(['store', 'update', 'destroy']);

});

require __DIR__.'/settings.php';
