<?php

namespace Database\Seeders;

use App\Models\ShiftRepeat;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ShiftRepeatSeeder extends Seeder
{
    public function run(): void
    {
        ShiftRepeat::create([
            'total_days' => 91,
            'shift_pattern_start_date' => '2026-01-26',
            ]);
    }
}
