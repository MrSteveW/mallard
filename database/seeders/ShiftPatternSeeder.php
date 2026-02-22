<?php

namespace Database\Seeders;

use App\Models\ShiftPattern;
use Illuminate\Database\Seeder;
use App\Enums\ShiftStatus;

class ShiftPatternSeeder extends Seeder
{

    public function run(): void
    {
       ShiftPattern::create([
            'user_id' => 1,
            'day_number' => 1,
            'status' => ShiftStatus::OnDuty,
            'start_time' => '19:30',
            'end_time' => '08:00',
            ]);
        ShiftPattern::create([
            'user_id' => 1,
            'day_number' => 2,
            'status' => ShiftStatus::OnDuty,
            'start_time' => '19:30',
            'end_time' => '08:00',
            ]);
        ShiftPattern::create([
            'user_id' => 1,
            'day_number' => 3,
            'status' => ShiftStatus::OnDuty,
            'start_time' => '19:30',
            'end_time' => '08:00',
            ]);
         ShiftPattern::create([
            'user_id' => 1,
            'day_number' => 4,
            'status' => ShiftStatus::Off,
            ]);
        ShiftPattern::create([
            'user_id' => 1,
            'day_number' => 5,
            'status' => ShiftStatus::Off,
            ]);


        ShiftPattern::create([
            'user_id' => 2,
            'day_number' => 1,
            'status' => ShiftStatus::Off,
            ]);
        ShiftPattern::create([
            'user_id' => 2,
            'day_number' => 2,
            'status' => ShiftStatus::Off,
            ]);
        ShiftPattern::create([
            'user_id' => 2,
            'day_number' => 3,
            'status' => ShiftStatus::OnDuty,
            'start_time' => '08:00',
            'end_time' => '20:00',
            ]);
         ShiftPattern::create([
            'user_id' => 2,
            'day_number' => 4,
            'status' => ShiftStatus::OnDuty,
            'start_time' => '19:30',
            'end_time' => '08:00',
            ]);
         ShiftPattern::create([
            'user_id' => 2,
            'day_number' => 5,
            'status' => ShiftStatus::OnDuty,
            'start_time' => '19:30',
            'end_time' => '08:00',
            ]);

        
         ShiftPattern::create([
            'user_id' => 3,
            'day_number' => 1,
            'status' => ShiftStatus::Off,
            ]);
        ShiftPattern::create([
            'user_id' => 3,
            'day_number' => 2,
            'status' => ShiftStatus::Off,
            ]);
        ShiftPattern::create([
            'user_id' => 3,
            'day_number' => 3,
            'status' => ShiftStatus::Off,
            ]);
         ShiftPattern::create([
            'user_id' => 3,
            'day_number' => 4,
            'status' => ShiftStatus::OnDuty,
            'start_time' => '07:45',
            'end_time' => '15:45',
            ]);
         ShiftPattern::create([
            'user_id' => 3,
            'day_number' => 5,
            'status' => ShiftStatus::OnDuty,
            'start_time' => '07:45',
            'end_time' => '15:45',
            ]);
    }
}
