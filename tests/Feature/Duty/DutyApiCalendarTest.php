<?php

use App\Models\Duty;
use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

$start = '2026-04-01';
$end = '2026-04-30';

it('returns 401 for unauthenticated requests', function () use ($start, $end) {
    $this->getJson("/api/duties?start={$start}&end={$end}")
        ->assertUnauthorized();
});

it('allows admin to fetch duties', function () use ($start, $end) {
    $this->actingAs(User::factory()->admin()->create())
        ->getJson("/api/duties?start={$start}&end={$end}")
        ->assertSuccessful();
});

it('allows authoriser to fetch duties', function () use ($start, $end) {
    $this->actingAs(User::factory()->authoriser()->create())
        ->getJson("/api/duties?start={$start}&end={$end}")
        ->assertSuccessful();
});

it('allows user to fetch duties', function () use ($start, $end) {
    $this->actingAs(User::factory()->user()->create())
        ->getJson("/api/duties?start={$start}&end={$end}")
        ->assertSuccessful();
});

it('allows guest to fetch duties', function () use ($start, $end) {
    $this->actingAs(User::factory()->guest()->create())
        ->getJson("/api/duties?start={$start}&end={$end}")
        ->assertSuccessful();
});

it('returns duties within the requested date range', function () use ($start, $end) {
    $user = User::factory()->admin()->create();
    $duty = Duty::factory()->create(['user_id' => $user->id, 'date' => '2026-04-15']);

    $this->actingAs($user)
        ->getJson("/api/duties?start={$start}&end={$end}")
        ->assertSuccessful()
        ->assertJsonFragment(['id' => $duty->id]);
});

it('excludes duties outside the requested date range', function () use ($start, $end) {
    $user = User::factory()->admin()->create();
    $duty = Duty::factory()->create(['user_id' => $user->id, 'date' => '2026-05-15']);

    $this->actingAs($user)
        ->getJson("/api/duties?start={$start}&end={$end}")
        ->assertSuccessful()
        ->assertJsonMissing(['id' => $duty->id]);
});

it('excludes cancelled duties by default', function () use ($start, $end) {
    $user = User::factory()->admin()->create();
    $duty = Duty::factory()->create([
        'user_id' => $user->id,
        'date' => '2026-04-15',
        'cancelled_at' => now(),
    ]);

    $this->actingAs($user)
        ->getJson("/api/duties?start={$start}&end={$end}")
        ->assertSuccessful()
        ->assertJsonMissing(['id' => $duty->id]);
});

it('includes cancelled duties when include_cancelled is true', function () use ($start, $end) {
    $user = User::factory()->admin()->create();
    $duty = Duty::factory()->create([
        'user_id' => $user->id,
        'date' => '2026-04-15',
        'cancelled_at' => now(),
    ]);

    $this->actingAs($user)
        ->getJson("/api/duties?start={$start}&end={$end}&include_cancelled=1")
        ->assertSuccessful()
        ->assertJsonFragment(['id' => $duty->id]);
});

it('returns the correct FullCalendar resource structure', function () use ($start, $end) {
    $user = User::factory()->admin()->create();
    $duty = Duty::factory()->create([
        'user_id' => $user->id,
        'date' => '2026-04-15',
        'shift_type' => 'Early',
        'start_time' => '08:00',
        'end_time' => '16:00',
    ]);

    $this->actingAs($user)
        ->getJson("/api/duties?start={$start}&end={$end}")
        ->assertSuccessful()
        ->assertJsonFragment([
            'id' => $duty->id,
            'title' => $user->name,
            'start' => '2026-04-15T08:00:00',
            'end' => '2026-04-15T16:00:00',
        ])
        ->assertJsonFragment([
            'user_id' => $user->id,
            'shift_type' => 'Early',
            'start_time' => '08:00',
            'end_time' => '16:00',
        ]);
});

it('requires start parameter', function () use ($end) {
    $this->actingAs(User::factory()->admin()->create())
        ->getJson("/api/duties?end={$end}")
        ->assertUnprocessable();
});

it('requires end parameter', function () use ($start) {
    $this->actingAs(User::factory()->admin()->create())
        ->getJson("/api/duties?start={$start}")
        ->assertUnprocessable();
});
