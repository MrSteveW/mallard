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
