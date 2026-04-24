<?php

namespace Database\Factories;

use App\Models\Duty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Duty>
 */
class DutyFactory extends Factory
{
    protected $model = Duty::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date' => fake()->date(),
            'shift_type' => 'Early',
            'start_time' => '08:00',
            'end_time' => '16:00',
            'duration' => 480,
        ];
    }
}
