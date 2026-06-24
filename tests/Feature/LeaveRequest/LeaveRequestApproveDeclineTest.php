<?php

use App\Models\LeaveRequest;
use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

it('allows admin to approve a LeaveRequest', function () {
    $admin = User::factory()->admin()->create();
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $admin->id]);

    $response = $this->actingAs($admin)
        ->patch(route('leaverequests.manage.approve', $leaveRequest));
    $response->assertRedirect(route('leaverequests.manage.index'));

    expect($leaveRequest->fresh()->approved_by)->toBe($admin->id);
});

it('allows authoriser to approve a LeaveRequest', function () {
    $authoriser = User::factory()->authoriser()->create();
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $authoriser->id]);

    $response = $this->actingAs($authoriser)
        ->patch(route('leaverequests.manage.approve', $leaveRequest));
    $response->assertRedirect(route('leaverequests.manage.index'));

    expect($leaveRequest->fresh()->approved_by)->toBe($authoriser->id);
});

it('a user is forbidden from approving a LeaveRequest', function () {
    $user = User::factory()->user()->create();
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->patch(route('leaverequests.manage.approve', $leaveRequest));
    $response->assertForbidden();
});

it('a guest is forbidden from approving a LeaveRequest', function () {
    $guest = User::factory()->guest()->create();
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $guest->id]);

    $response = $this->actingAs($guest)
        ->patch(route('leaverequests.manage.approve', $leaveRequest));
    $response->assertForbidden();
});

it('allows admin to decline a LeaveRequest', function () {
    $admin = User::factory()->admin()->create();
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $admin->id]);

    $response = $this->actingAs($admin)
        ->patch(route('leaverequests.manage.decline', $leaveRequest));
    $response->assertRedirect(route('leaverequests.manage.index'));

    expect($leaveRequest->fresh()->declined_by)->toBe($admin->id);
});

it('allows authoriser to decline a LeaveRequest', function () {
    $authoriser = User::factory()->authoriser()->create();
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $authoriser->id]);

    $response = $this->actingAs($authoriser)
        ->patch(route('leaverequests.manage.decline', $leaveRequest));
    $response->assertRedirect(route('leaverequests.manage.index'));

    expect($leaveRequest->fresh()->declined_by)->toBe($authoriser->id);
});

it('a user is forbidden from declining a LeaveRequest', function () {
    $user = User::factory()->user()->create();
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->patch(route('leaverequests.manage.decline', $leaveRequest));
    $response->assertForbidden();
});

it('a guest is forbidden from declining a LeaveRequest', function () {
    $guest = User::factory()->guest()->create();
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $guest->id]);

    $response = $this->actingAs($guest)
        ->patch(route('leaverequests.manage.decline', $leaveRequest));
    $response->assertForbidden();
});