<?php

use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

test('can unathenticated user view dashboard', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('can admin view dashboard', function () {
    $user = User::factory()->admin()->create();
    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia();
});

test('can authoriser view dashboard', function () {
    $user = User::factory()->authoriser()->create();
    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia();
});

test('can user view dashboard', function () {
    $user = User::factory()->user()->create();
    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia();
});

test('can guest view dashboard', function () {
    $user = User::factory()->guest()->create();
    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertInertia();
});
