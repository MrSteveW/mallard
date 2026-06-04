<?php

use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

test('unauthenticated user is redirected from Leave Request index', function () {
    $this->get(route('leaverequests.index'))
        ->assertRedirect(route('login'));
});

test('can admin view leave requests index', function () {
    $user = User::factory()->admin()->create();
    $this->actingAs($user)
        ->get(route('leaverequests.index'))
        ->assertInertia();
});

test('can authoriser view leave requests index', function () {
    $user = User::factory()->authoriser()->create();
    $this->actingAs($user)
        ->get(route('leaverequests.index'))
        ->assertInertia();
});

test('can user view leave requests index', function () {
    $user = User::factory()->user()->create();
    $this->actingAs($user)
        ->get(route('leaverequests.index'))
        ->assertInertia();
});

test('can guest view leave requests index', function () {
    $user = User::factory()->guest()->create();
    $this->actingAs($user)
        ->get(route('leaverequests.index'))
        ->assertInertia();
});