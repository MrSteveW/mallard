<?php

use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

test('unauthenticated user is redirected from leave request manage index', function () {
    $this->get(route('leaverequests.manage.index'))
        ->assertRedirect(route('login'));
});

test('can admin view leave requests manage index', function () {
    $user = User::factory()->admin()->create();
    $this->actingAs($user)
        ->get(route('leaverequests.manage.index'))
        ->assertInertia();
});

test('can authoriser view leave requests manage index', function () {
    $user = User::factory()->authoriser()->create();
    $this->actingAs($user)
        ->get(route('leaverequests.manage.index'))
        ->assertInertia();
});

test('user is forbidden from viewing leave requests manage index', function () {
    $user = User::factory()->user()->create();
    $this->actingAs($user)
        ->get(route('leaverequests.manage.index'))
        ->assertForbidden();
});

test('can guest view leave requests manage index', function () {
    $user = User::factory()->guest()->create();
    $this->actingAs($user)
        ->get(route('leaverequests.manage.index'))
        ->assertInertia();
});