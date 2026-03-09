<?php

namespace App\Actions;

use App\Models\User;
use App\Models\ShiftPattern;
use App\Enums\ShiftType;


class InitialiseShiftPatterns
{
    public function handle(User $user, int $totalDays): void
    {
        collect(range(1, $totalDays))->each(
            fn($day) => ShiftPattern::firstOrCreate(
                ['user_id' => $user->id, 'day' => $day],
                ['shift_type' => ShiftType::Off]
            )
        );
    }
}