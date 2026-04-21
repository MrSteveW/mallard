<?php

use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

test('unauthenticated user is redirected from duties showTasks', function () {
    $this->get(route('duties.showTasks', ['date' => '2025-04-21']))
        ->assertRedirect(route('login'));
});

test('can admin view duties showTasks', function () {
    $user = User::factory()->admin()->create();
    $this->actingAs($user)
        ->get(route('duties.showTasks', ['date' => '2025-04-21']))
        ->assertInertia();
});

test('can authoriser view duties showTasks', function () {
    $user = User::factory()->authoriser()->create();
    $this->actingAs($user)
        ->get(route('duties.showTasks', ['date' => '2025-04-21']))
        ->assertInertia();
});

test('user is forbidden from viewing duties showTasks', function () {
    $user = User::factory()->user()->create();
    $this->actingAs($user)
        ->get(route('duties.showTasks', ['date' => '2025-04-21']))
        ->assertForbidden();
});

test('can guest view duties showTasks', function () {
    $user = User::factory()->guest()->create();
    $this->actingAs($user)
        ->get(route('duties.showTasks', ['date' => '2025-04-21']))
        ->assertInertia();
});
