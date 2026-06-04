<?php

namespace Database\Seeders;

use App\Enums\LeaveOptions;
use App\Enums\UserRole;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaveRequestSeeder extends Seeder
{
    public function run(): void
    {
        LeaveRequest::create([
            'user_id' => '1',
            'dates' => ['2026-06-20'],
            'start_time' => '12:00',
            'end_time' => '16:00',
            'notes' => 'Upcoming LR partial_day pending',
            'leave_reason' => LeaveOptions::AnnualLeave,
        ]);
        LeaveRequest::create([
            'user_id' => '1',
            'dates' => ['2026-06-21'],
            'start_time' => '13:00',
            'end_time' => '16:00',
            'notes' => 'Upcoming LR partial_day authorised',
            'leave_reason' => LeaveOptions::AnnualLeave,
            'approved_by' => '1',
        ]);
        LeaveRequest::create([
            'user_id' => '1',
            'dates' => ['2026-06-22'],
            'start_time' => '14:00',
            'end_time' => '16:00',
            'notes' => 'Upcoming LR partial_day declined',
            'leave_reason' => LeaveOptions::CompassionateLeave,
            'declined_by' => '2',
        ]);
        LeaveRequest::create([
            'user_id' => '1',
            'dates' => ['2026-06-23', '2026-06-25'],
            'notes' => 'Upcoming LR multiple days pending',
            'leave_reason' => LeaveOptions::CompassionateLeave,
        ]);
        LeaveRequest::create([
            'user_id' => '1',
            'dates' => ['2026-06-26', '2026-06-27', '2026-06-28'],
            'notes' => 'Upcoming LR range days pending',
            'leave_reason' => LeaveOptions::CompassionateLeave,
        ]);
        LeaveRequest::create([
            'user_id' => '1',
            'dates' => ['2025-05-28'],
            'notes' => 'Previous LR not actioned',
            'leave_reason' => LeaveOptions::CompassionateLeave,
        ]);
        LeaveRequest::create([
            'user_id' => '1',
            'dates' => ['2024-05-28'],
            'start_time' => '20:00',
            'end_time' => '08:00',
            'notes' => 'Previous LR authorised',
            'leave_reason' => LeaveOptions::CompassionateLeave,
            'approved_by' => '2',
        ]);
        LeaveRequest::create([
            'user_id' => '3',
            'dates' => ['2024-05-28'],
            'start_time' => '08:00',
            'end_time' => '14:00',
            'notes' => 'Previous LR on same day',
            'leave_reason' => LeaveOptions::Sickness,
        ]);
        LeaveRequest::create([
            'user_id' => '1',
            'dates' => ['2025-05-28'],
            'start_time' => '20:00',
            'end_time' => '08:00',
            'notes' => 'Previous LR declined',
            'leave_reason' => LeaveOptions::CompassionateLeave,
            'declined_by' => '2',
        ]);
        LeaveRequest::create([
            'user_id' => '3',
            'dates' => ['2026-07-30'],
            'start_time' => '08:00',
            'end_time' => '16:00',
            'notes' => 'Upcoming LR requested by an Authoriser',
            'leave_reason' => LeaveOptions::Training,
        ]);
        LeaveRequest::create([
            'user_id' => '3',
            'dates' => ['2026-07-31'],
            'start_time' => '08:00',
            'end_time' => '16:00',
            'notes' => 'Upcoming LR requested by a User',
            'leave_reason' => LeaveOptions::Training,
        ]);
        LeaveRequest::create([
            'user_id' => '4',
            'dates' => ['2026-07-31'],
            'start_time' => '08:00',
            'end_time' => '16:00',
            'notes' => 'Upcoming LR on the same day',
            'leave_reason' => LeaveOptions::Bereavement,
        ]);

        $guest = User::where('role', UserRole::Guest)->sole();

        LeaveRequest::create([
            'user_id' => $guest->id,
            'dates' => ['2026-08-01'],
            'start_time' => '12:00',
            'end_time' => '16:00',
            'notes' => 'Upcoming LR partial_day pending',
            'leave_reason' => LeaveOptions::AnnualLeave,
        ]);
        LeaveRequest::create([
            'user_id' => $guest->id,
            'dates' => ['2026-09-25'],
            'start_time' => '13:00',
            'end_time' => '16:00',
            'notes' => 'Upcoming LR partial_day authorised',
            'leave_reason' => LeaveOptions::Training,
            'approved_by' => '1',
        ]);
        LeaveRequest::create([
            'user_id' => $guest->id,
            'dates' => ['2026-07-10'],
            'start_time' => '14:00',
            'end_time' => '16:00',
            'notes' => 'Upcoming LR partial_day declined',
            'leave_reason' => LeaveOptions::Medical,
            'declined_by' => '2',
        ]);
        LeaveRequest::create([
            'user_id' => $guest->id,
            'dates' => ['2026-07-15', '2026-07-18'],
            'notes' => 'Upcoming LR multiple days pending',
            'leave_reason' => LeaveOptions::CompassionateLeave,
        ]);
        LeaveRequest::create([
            'user_id' => $guest->id,
            'dates' => ['2026-07-26', '2026-07-27', '2026-07-28'],
            'notes' => 'Upcoming LR range days pending',
            'leave_reason' => LeaveOptions::PaternityLeave,
        ]);
    }
}
