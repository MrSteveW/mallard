<?php

use App\Models\Duty;
use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

it('allows admin to update a Duty', function () {
    $admin = User::factory()->admin()->create();
    $duty = Duty::factory()->create(['user_id' => $admin->id]);

    $updated = Duty::factory()->make(['user_id' => $admin->id]);

    $this->actingAs($admin)
        ->put(route('duties.update', $duty), $updated->toArray())
        ->assertRedirect();

    $this->assertDatabaseHas('duties', [
        'id' => $duty->id,
        'date' => $updated->date,
        'shift_type' => $updated->shift_type,
    ]);
});
