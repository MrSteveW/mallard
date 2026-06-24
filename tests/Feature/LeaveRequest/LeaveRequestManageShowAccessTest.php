<?php

use App\Models\Employee;
use App\Models\Grade;
use App\Models\LeaveRequest;
use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

test('unauthenticated user is redirected from leave request manage show', function () {
    $user = User::factory()->create();
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $user->id]);

    $this->get(route('leaverequests.manage.show', $leaveRequest))
        ->assertRedirect(route('login'));
});

test('can admin view leave request manage show', function () {
    $grade = Grade::factory()->create();
    $admin = User::factory()->admin()->create();
    Employee::create(['user_id' => $admin->id, 'grade_id' => $grade->id]);
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $admin->id]);

    $this->actingAs($admin)
        ->get(route('leaverequests.manage.show', $leaveRequest))
        ->assertInertia();
});

test('can authoriser view leave request manage show', function () {
    $grade = Grade::factory()->create();
    $authoriser = User::factory()->authoriser()->create();
    Employee::create(['user_id' => $authoriser->id, 'grade_id' => $grade->id]);
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $authoriser->id]);

    $this->actingAs($authoriser)
        ->get(route('leaverequests.manage.show', $leaveRequest))
        ->assertInertia();
});

test('can guest view leave request manage show', function () {
    $grade = Grade::factory()->create();
    $guest = User::factory()->guest()->create();
    Employee::create(['user_id' => $guest->id, 'grade_id' => $grade->id]);
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $guest->id]);

    $this->actingAs($guest)
        ->get(route('leaverequests.manage.show', $leaveRequest))
        ->assertInertia();
});

test('user is forbidden from viewing leave request manage show', function () {
    $user = User::factory()->user()->create();
    $leaveRequest = LeaveRequest::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('leaverequests.manage.show', $leaveRequest))
        ->assertForbidden();
});
