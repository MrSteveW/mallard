<?php

use App\Http\Controllers\CalendarNoteController;
use App\Http\Controllers\DutyController;
use App\Http\Controllers\ShiftPatternController;
use App\Http\Resources\CalendarNoteResource;
use App\Models\CalendarNote;
use App\Models\Duty;
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

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard', [
            'calendarNotes' => CalendarNoteResource::collection(CalendarNote::orderBy('date')->get()),
        ]);
    })->name('dashboard');

    Route::resource('shiftpatterns', ShiftPatternController::class)
        ->parameters(['shiftpatterns' => 'user'])
        ->only(['index', 'store', 'edit', 'update']);;

    Route::resource('calendar-notes', CalendarNoteController::class)
        ->only(['store', 'update', 'destroy']);
});

// Admin || Authoriser || Guest
Route::middleware(['auth', 'can:manage,'.Duty::class])->group(function () {
    Route::get('/duties/{date}/tasks', [DutyController::class, 'showTasks'])
           ->name('duties.showTasks');
    Route::resource('duties', DutyController::class)->only(['index','store', 'update', 'destroy']);
    Route::post('duties/generate', [DutyController::class, 'generate']);
    Route::patch('duties/{duty}/cancel', [DutyController::class, 'cancel']);
    Route::patch('/duties/{date}/tasks', [DutyController::class, 'updateTasks']);
});


require __DIR__.'/settings.php';
