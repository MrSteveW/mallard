<?php

use App\Models\LeaveRequest;
use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

it('allows admin to create a LeaveRequest', function () {
    $admin = User::factory()->admin()->create();
    $leaveRequest = LeaveRequest::factory()->make(['user_id' => $admin->id]);

    $this->actingAs($admin)
        ->post(route('leaverequests.store'), $leaveRequest->toArray())
        ->assertRedirect('/leaverequests');

    $this->assertDatabaseHas('leave_requests', [
        'user_id' => $admin->id,
        'dates' => json_encode($leaveRequest->dates),
        'leave_reason' => $leaveRequest->leave_reason,
    ]);
});

it('allows authoriser to create a LeaveRequest', function () {
    $authoriser = User::factory()->authoriser()->create();
    $leaveRequest = LeaveRequest::factory()->make(['user_id' => $authoriser->id]);

    $this->actingAs($authoriser)
        ->post(route('leaverequests.store'), $leaveRequest->toArray())
        ->assertRedirect('/leaverequests');

    $this->assertDatabaseHas('leave_requests', [
        'user_id' => $authoriser->id,
        'dates' => json_encode($leaveRequest->dates),
        'leave_reason' => $leaveRequest->leave_reason,
    ]);
});

it('allows user to create a LeaveRequest', function () {
    $user = User::factory()->user()->create();
    $leaveRequest = LeaveRequest::factory()->make(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post(route('leaverequests.store'), $leaveRequest->toArray())
        ->assertRedirect('/leaverequests');

    $this->assertDatabaseHas('leave_requests', [
        'user_id' => $user->id,
        'dates' => json_encode($leaveRequest->dates),
        'leave_reason' => $leaveRequest->leave_reason,
    ]);
});

it('guest is forbidden from creating a LeaveRequest', function () {
    $user = User::factory()->guest()->create();
    $leaveRequest = LeaveRequest::factory()->make(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post(route('leaverequests.store'), $leaveRequest->toArray())
        ->assertForbidden();
});