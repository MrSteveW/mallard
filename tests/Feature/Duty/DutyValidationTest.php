
<?php

use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

it('rejects missing user_id', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('duties.store'), [])
        ->assertSessionHasErrors('user_id');
});

it('rejects non-existent user_id', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('duties.store'), ['user_id' => PHP_INT_MAX])
        ->assertSessionHasErrors('user_id');
});

it('rejects non-existent task_id', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('duties.store'), ['task_id' => PHP_INT_MAX])
        ->assertSessionHasErrors('task_id');
});

it('rejects invalid date formats', function (string $date) {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin)
        ->post(route('duties.store'), ['date' => $date])
        ->assertSessionHasErrors('date');
})->with([
    'wrong separator' => ['01/04/2026'],
    'UK order' => ['22-04-2026'],
    'US format' => ['04-22-2026'],
    'plain text' => ['not-a-date'],
    'empty string' => [''],
]);

it('rejects an invalid shift_type', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('duties.store'), ['shift_type' => 'Foo'])
        ->assertSessionHasErrors('shift_type');
});

it('rejects invalid start_time formats', function (string $time) {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('duties.store'), ['start_time' => $time])
        ->assertSessionHasErrors('start_time');
})->with([
    'invalid hour' => ['25:00'],
    'invalid minutes' => ['14:17'],
    'plain text' => ['not-a-time'],
    'empty string' => [''],
]);

it('rejects invalid end_time formats', function (string $time) {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('duties.store'), ['end_time' => $time])
        ->assertSessionHasErrors('end_time');
})->with([
    'invalid hour' => ['25:00'],
    'invalid minutes' => ['14:17'],
    'plain text' => ['not-a-time'],
    'empty string' => [''],
]);

it('stores the correct duration', function (string $start, string $end, int $expectedMinutes) {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('duties.store'), [
            'user_id' => $admin->id,
            'date' => '2026-04-23',
            'shift_type' => 'Early',
            'start_time' => $start,
            'end_time' => $end,
        ])
        ->assertRedirect('/duties');

    $this->assertDatabaseHas('duties', [
        'start_time' => $start,
        'end_time' => $end,
        'duration' => $expectedMinutes,
    ]);
})->with([
    'day shift' => ['08:00', '16:00', 480],
    'overnight shift' => ['20:00', '06:00', 600],
    'overnight edge' => ['23:45', '00:00', 15],
    'equal times' => ['06:00', '06:00', 1440],
]);
