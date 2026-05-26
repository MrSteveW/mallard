<?php

namespace Database\Seeders;

use App\Enums\LeaveOptions;
use App\Models\LeaveRequest;
use Illuminate\Database\Seeder;

class LeaveRequestSeeder extends Seeder
{
    public function run(): void
    {
        LeaveRequest::create([
            'user_id'    => '1',
            'date'       => '2026-05-28',
            'start_time' => '08:00',
            'end_time'   => '16:00',
            'duration'   => '480',
            'notes'      => 'Upcoming LR not actioned',
            'leave_reason' => LeaveOptions::AnnualLeave,
        ]);
        LeaveRequest::create([
            'user_id'    => '1',
            'date'       => '2026-05-28',
            'start_time' => '08:00',
            'end_time'   => '16:00',
            'duration'   => '480',
            'notes'      => 'Upcoming LR authorised',
            'leave_reason' => LeaveOptions::AnnualLeave,
            'approved_by' => '1',
        ]);
        LeaveRequest::create([
            'user_id'    => '1',
            'date'       => '2026-06-01',
            'start_time' => '20:00',
            'end_time'   => '08:00',
            'duration'   => '720',
            'notes'      => 'Upcoming LR declined',
            'leave_reason' => LeaveOptions::CompassionateLeave,
            'declined_by' => '2',
        ]);
        LeaveRequest::create([
            'user_id'    => '1',
            'date'       => '2025-05-28',
            'start_time' => '20:00',
            'end_time'   => '08:00',
            'duration'   => '720',
            'notes'      => 'Previous LR not actioned',
            'leave_reason' => LeaveOptions::CompassionateLeave,
        ]);
        LeaveRequest::create([
            'user_id'    => '1',
            'date'       => '2024-05-28',
            'start_time' => '20:00',
            'end_time'   => '08:00',
            'duration'   => '720',
            'notes'      => 'Previous LR authorised',
            'leave_reason' => LeaveOptions::CompassionateLeave,
            'approved_by' => '2',
        ]);
        LeaveRequest::create([
            'user_id'    => '3',
            'date'       => '2024-05-28',
            'start_time' => '08:00',
            'end_time'   => '14:00',
            'duration'   => '480',
            'notes'      => 'Previous LR on same day',
            'leave_reason' => LeaveOptions::Sickness,
        ]);
        LeaveRequest::create([
            'user_id'    => '1',
            'date'       => '2025-05-28',
            'start_time' => '20:00',
            'end_time'   => '08:00',
            'duration'   => '720',
            'notes'      => 'Previous LR declined',
            'leave_reason' => LeaveOptions::CompassionateLeave,
            'declined_by' => '2',
        ]);
        LeaveRequest::create([
            'user_id'    => '3',
            'date'       => '2026-07-30',
            'start_time' => '08:00',
            'end_time'   => '16:00',
            'duration'   => '480',
            'notes'      => 'Upcoming LR requested by an Authoriser',
            'leave_reason' => LeaveOptions::Training,
        ]);
        LeaveRequest::create([
            'user_id'    => '3',
            'date'       => '2026-07-31',
            'start_time' => '08:00',
            'end_time'   => '16:00',
            'duration'   => '480',
            'notes'      => 'Upcoming LR requested by a User',
            'leave_reason' => LeaveOptions::Training,
        ]);
        LeaveRequest::create([
            'user_id'    => '4',
            'date'       => '2026-07-31',
            'start_time' => '08:00',
            'end_time'   => '16:00',
            'duration'   => '480',
            'notes'      => 'Upcoming LR on the same day',
            'leave_reason' => LeaveOptions::Bereavement,
        ]);
    }
}
