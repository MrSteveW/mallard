<?php

namespace Database\Factories;

use App\Enums\LeaveOptions;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mode' => 'multiple_days',
            'leave_reason' => LeaveOptions::AnnualLeave->value,
            'dates' => ['2030-01-01'],
        ];
    }
}
