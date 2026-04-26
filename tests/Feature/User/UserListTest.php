<?php

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

it('allows admin to load the user list page', function () {
    User::factory()->count(3)->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(ListUsers::class)
        ->assertOk()
        ->assertCanSeeTableRecords(User::all());
});

it('allows guest to load the user list page', function () {
    User::factory()->count(3)->create();

    $this->actingAs(User::factory()->guest()->create());

    Livewire::test(ListUsers::class)
        ->assertOk()
        ->assertCanSeeTableRecords(User::all());
});

it('prevents authoriser from loading the user list page', function () {
    $this->actingAs(User::factory()->authoriser()->create());

    Livewire::test(ListUsers::class)
        ->assertForbidden();
});

it('prevents user from loading the user list page', function () {
    $this->actingAs(User::factory()->user()->create());

    Livewire::test(ListUsers::class)
        ->assertForbidden();
});
