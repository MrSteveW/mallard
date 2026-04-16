<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ShiftRepeatSeeder::class,
            UserSeeder::class,
            EmployeeSeeder::class,
            ShiftPatternOneSeeder::class,
            ShiftPatternTwoSeeder::class,
            ShiftPatternThreeSeeder::class,
            ShiftPatternFourSeeder::class,
            ShiftPatternFiveSeeder::class,
            ShiftPatternSixSeeder::class,
            ShiftPatternSevenSeeder::class,
            ShiftPatternEightSeeder::class,
            ShiftPatternNineSeeder::class,
            ShiftPatternTenSeeder::class,
            ShiftPatternElevenSeeder::class,
            ShiftPatternTwelveSeeder::class,
            GradeSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
