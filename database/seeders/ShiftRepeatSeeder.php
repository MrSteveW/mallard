<?php

namespace Database\Seeders;

use App\Models\ShiftRepeat;
use Illuminate\Database\Seeder;

class ShiftRepeatSeeder extends Seeder
{
    public function run(): void
    {
        ShiftRepeat::updateOrCreate([
            'total_days' => 91,
            'shift_pattern_start_date' => '2026-01-26',
        ]);
    }
}
