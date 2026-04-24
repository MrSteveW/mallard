<?php

use App\Models\Duty;
use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

it('allows admin to create a Duty', function () {
    $admin = User::factory()->admin()->create();
    $duty = Duty::factory()->make(['user_id' => $admin->id]);

    $this->actingAs($admin)
        ->post(route('duties.store'), $duty->toArray())
        ->assertRedirect('/duties');

    $this->assertDatabaseHas('duties', [
        'user_id' => $admin->id,
        'date' => $duty->date,
        'shift_type' => $duty->shift_type,
    ]);
});

it('allows authoriser to create a Duty', function () {
    $authoriser = User::factory()->authoriser()->create();
    $duty = Duty::factory()->make(['user_id' => $authoriser->id]);

    $this->actingAs($authoriser)
        ->post(route('duties.store'), $duty->toArray())
        ->assertRedirect('/duties');

    $this->assertDatabaseHas('duties', [
        'user_id' => $authoriser->id,
        'date' => $duty->date,
        'shift_type' => $duty->shift_type,
    ]);
});

it('is forbidden for user to create a Duty', function () {
    $user = User::factory()->user()->create();
    $duty = Duty::factory()->make(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post(route('duties.store'), $duty->toArray())
        ->assertForbidden();
});

it('is forbidden for guest to create a Duty', function () {
    $guest = User::factory()->guest()->create();
    $duty = Duty::factory()->make(['user_id' => $guest->id]);

    $this->actingAs($guest)
        ->post(route('duties.store'), $duty->toArray())
        ->assertForbidden();
});
