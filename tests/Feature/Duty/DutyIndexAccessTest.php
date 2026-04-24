<?php

use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

test('unauthenticated user is redirected from duties index', function () {
    $this->get(route('duties.index'))
        ->assertRedirect(route('login'));
});

test('can admin view duties index', function () {
    $user = User::factory()->admin()->create();
    $this->actingAs($user)
        ->get(route('duties.index'))
        ->assertInertia();
});

test('can authoriser view duties index', function () {
    $user = User::factory()->authoriser()->create();
    $this->actingAs($user)
        ->get(route('duties.index'))
        ->assertInertia();
});

test('user is forbidden from viewing duties index', function () {
    $user = User::factory()->user()->create();
    $this->actingAs($user)
        ->get(route('duties.index'))
        ->assertForbidden();
});

test('can guest view duties index', function () {
    $user = User::factory()->guest()->create();
    $this->actingAs($user)
        ->get(route('duties.index'))
        ->assertInertia();
});
