<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::create([
            'user_id' => 1,
            'grade_id' => 4,
            'date_started' => now()->format('Y-m-d'),
            'training' => 'JavaScript, PHP',
            ]);

            Employee::create([
            'user_id' => 2,
            'grade_id' => 3,
            'date_started' => now()->format('Y-m-d'),
            'training' => 'Pipette training',
            ]);

            Employee::create([
            'user_id' => 3,
            'grade_id' => 1,
            'date_started' => now()->format('Y-m-d'),
            ]);

             Employee::create([
            'user_id' => 4,
            'grade_id' => 2,
            'date_started' => now()->format('Y-m-d'),
            ]);

             Employee::create([
            'user_id' => 5,
            'grade_id' => 2,
            'date_started' => now()->format('Y-m-d'),
            ]);

            Employee::create([
            'user_id' => 6,
            'grade_id' => 4,
            'date_started' => now()->format('Y-m-d'),
            ]);

            Employee::create([
            'user_id' => 7,
            'grade_id' => 1,
            'date_started' => now()->format('Y-m-d'),
            ]);

            Employee::create([
            'user_id' => 8,
            'grade_id' => 3,
            'date_started' => now()->format('Y-m-d'),
            ]);

            Employee::create([
            'user_id' => 9,
            'grade_id' => 1,
            'date_started' => now()->format('Y-m-d'),
            ]);

            Employee::create([
            'user_id' => 10,
            'grade_id' => 2,
            'date_started' => now()->format('Y-m-d'),
            ]);

            Employee::create([
            'user_id' => 11,
            'grade_id' => 2,
            'date_started' => now()->format('Y-m-d'),
            ]);

            Employee::create([
            'user_id' => 12,
            'grade_id' => 3,
            'date_started' => now()->format('Y-m-d'),
            ]);

            Employee::create([
            'user_id' => 13,
            'grade_id' => 4,
            'date_started' => now()->format('Y-m-d'),
            ]);

            Employee::create([
            'user_id' => 14,
            'grade_id' => 2,
            'date_started' => now()->format('Y-m-d'),
            ]);

            Employee::create([
            'user_id' => 15,
            'grade_id' => 2,
            'date_started' => now()->format('Y-m-d'),
            ]);

            // Employee::create([
            // 'user_id' => 16,
            // 'grade_id' => 1,
            // 'date_started' => now()->format('Y-m-d'),
            // ]);

            // Employee::create([
            // 'user_id' => 17,
            // 'grade_id' => 4,
            // 'date_started' => now()->format('Y-m-d'),
            // ]);

            // Employee::create([
            // 'user_id' => 18,
            // 'grade_id' => 3,
            // 'date_started' => now()->format('Y-m-d'),
            // ]);

            // Employee::create([
            // 'user_id' => 19,
            // 'grade_id' => 1,
            // 'date_started' => now()->format('Y-m-d'),
            // ]);

            // Employee::create([
            // 'user_id' => 20,
            // 'grade_id' => 2,
            // 'date_started' => now()->format('Y-m-d'),
            // ]);
       
    }
}
