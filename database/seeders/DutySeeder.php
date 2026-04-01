<?php

namespace Database\Seeders;


use App\Models\Duty;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DutySeeder extends Seeder
{
    public function run(): void
    {
        Duty::create([
            'user_id' => 1,
            'task_id' => 4,
            'date' => now()->format('Y-m-d'),
            'shift_type' => 'Early',
            'start_time' => '08:00',
            'end_time' => '20:00',
            'duration' => 720,
            'notes' => 'Data from seeeder',
            ]);
        Duty::create([
            'user_id' => 2,
            'task_id' => 3,
            'date' => Carbon::create(2026, 3, 2)->toDateString(),
            'shift_type' => 'Late',
            'start_time' => '12:00',
            'end_time' => '20:00',
            'duration' => 480,
            'notes' => 'Data from seeeder',
            ]);
        Duty::create([
            'user_id' => 1,
            'task_id' => 1,
            'date' => Carbon::create(2026, 3, 12)->toDateString(),
            'shift_type' => 'Late2',
            'start_time' => '14:00',
            'end_time' => '22:00',
            'duration' => 480,
            'notes' => 'Data from seeeder',
            ]);
        Duty::create([
            'user_id' => 3,
            'task_id' => 2,
            'date' => Carbon::create(2026, 3, 19)->toDateString(),
            'shift_type' => 'Night',
            'start_time' => '20:00',
            'end_time' => '08:00',
            'duration' => 720,
            'notes' => 'Testing id is for event',
            ]);
    }
}