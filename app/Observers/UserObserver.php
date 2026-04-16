<?php

namespace App\Observers;

use App\Actions\InitialiseShiftPatterns;
use App\Models\ShiftRepeat;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $totalDays = ShiftRepeat::sole()->total_days;
        (new InitialiseShiftPatterns)->handle($user, $totalDays);
    }
}
