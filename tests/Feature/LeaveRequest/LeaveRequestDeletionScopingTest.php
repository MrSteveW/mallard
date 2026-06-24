<?php

use App\Enums\LeaveOptions;
use App\Models\LeaveRequest;
use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

test('user can only delete their own leave requests', function () {
    $user = User::factory()->user()->create();
    $otherUser = User::factory()->user()->create();

    $leaveRequest = LeaveRequest::create([
        'user_id' => $user->id,
        'leave_reason' => LeaveOptions::AnnualLeave->value,
        'dates' => ['2030-01-01'],
    ]);
    $otherLeaveRequest = LeaveRequest::create([
        'user_id' => $otherUser->id,
        'leave_reason' => LeaveOptions::AnnualLeave->value,
        'dates' => ['2030-01-01'],
    ]);

    $response = $this->actingAs($user)
        ->delete(route('leaverequests.destroy', $leaveRequest));

    $response->assertRedirect();
    $this->assertDatabaseMissing('leave_requests', [
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)
        ->delete(route('leaverequests.destroy', $otherLeaveRequest));

    $response->assertForbidden();
});

test('user cannot delete an approved leave request', function () {
    $user = User::factory()->user()->create();

    $leaveRequest = LeaveRequest::create([
        'user_id' => $user->id,
        'leave_reason' => LeaveOptions::AnnualLeave->value,
        'dates' => ['2030-01-01'],
        'approved_by' => '1',
    ]);

    $response = $this->actingAs($user)
        ->delete(route('leaverequests.destroy',
            $leaveRequest));
    $response->assertForbidden();
});

test('user cannot delete a declined leave request', function () {
    $user = User::factory()->user()->create();

    $leaveRequest = LeaveRequest::create([
        'user_id' => $user->id,
        'leave_reason' => LeaveOptions::AnnualLeave->value,
        'dates' => ['2030-01-01'],
        'declined_by' => '1',
    ]);

    $response = $this->actingAs($user)
        ->delete(route('leaverequests.destroy',
            $leaveRequest));
    $response->assertForbidden();
});
