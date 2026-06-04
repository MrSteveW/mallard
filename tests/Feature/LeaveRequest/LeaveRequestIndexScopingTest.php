<?php

use App\Enums\LeaveOptions;
use App\Models\LeaveRequest;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

test('user can only see their own leave requests on index', function () {
    $user = User::factory()->user()->create();
    $otherUser = User::factory()->user()->create();

    $ownRequest = LeaveRequest::create([
        'user_id' => $user->id,
        'leave_reason' => LeaveOptions::AnnualLeave->value,
        'dates' => ['2030-01-01'],
    ]);
    LeaveRequest::create([
        'user_id' => $otherUser->id,
        'leave_reason' => LeaveOptions::AnnualLeave->value,
        'dates' => ['2030-01-01'],
    ]);

    $this->actingAs($user)
        ->get(route('leaverequests.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('LeaveRequest/Index')
            ->has('leaveRequests', 1)
            ->where('leaveRequests.0.id', $ownRequest->id)
        );
});