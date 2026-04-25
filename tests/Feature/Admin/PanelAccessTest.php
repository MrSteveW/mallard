<?php

use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

it('allows admin to access the panel', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get('/admin')
        ->assertSuccessful();
});

it('allows guest to access the panel', function () {
    $this->actingAs(User::factory()->guest()->create())
        ->get('/admin')
        ->assertSuccessful();
});

it('prevents authoriser from accessing the panel', function () {
    $this->actingAs(User::factory()->authoriser()->create())
        ->get('/admin')
        ->assertForbidden();
});

it('prevents user from accessing the panel', function () {
    $this->actingAs(User::factory()->user()->create())
        ->get('/admin')
        ->assertForbidden();
});

it('redirects unauthenticated visitors to login', function () {
    $this->get('/admin')
        ->assertRedirectContains('login');
});
