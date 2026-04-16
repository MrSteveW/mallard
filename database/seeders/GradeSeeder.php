<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        Grade::create([
            'name' => 'AP',
        ]);

        Grade::create([
            'name' => 'BMS5',
        ]);

        Grade::create([
            'name' => 'BMS6',
        ]);

        Grade::create([
            'name' => 'BMS7',
        ]);
    }
}
