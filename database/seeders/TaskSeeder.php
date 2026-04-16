<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::create([
            'name' => 'Analyser 1',
        ]);

        Task::create([
            'name' => 'Analyser 2',
        ]);

        Task::create([
            'name' => 'Pipetting',
        ]);

        Task::create([
            'name' => 'Bossing around',
        ]);
    }
}
