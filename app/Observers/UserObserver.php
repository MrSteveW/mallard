<?php

namespace App\Observers;

use App\Models\User;
use App\Models\ShiftRepeat;
use App\Actions\InitialiseShiftPatterns;

class UserObserver
{
    public function created(User $user): void
    {
        $totalDays = ShiftRepeat::sole()->total_days;
        (new InitialiseShiftPatterns)->handle($user, $totalDays);
    }
}