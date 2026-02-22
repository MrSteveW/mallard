<?php

namespace Database\Seeders;

use App\Models\ShiftRepeat;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ShiftRepeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShiftRepeat::create([
            'total_days' => 91,
            'start_on' => Carbon::createFromFormat('d-m-Y', '26-01-2026'),
            ]);
    }
}
