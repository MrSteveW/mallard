<?php

use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

it('redirects an unauthenticated user from dashboard', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

it('allows admin to view dashboard', function () {
    $user = User::factory()->admin()->create();
    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia();
});

it('allows authoriser to view dashboard', function () {
    $user = User::factory()->authoriser()->create();
    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia();
});

it('allows user to view dashboard', function () {
    $user = User::factory()->user()->create();
    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia();
});

it('allows guest to view dashboard', function () {
    $user = User::factory()->guest()->create();
    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia();
});
