<?php

use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Models\Task;
use App\Models\User;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

it('allows admin to load the task list page', function () {
    Task::factory()->count(3)->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(ListTasks::class)
        ->assertOk()
        ->assertCanSeeTableRecords(Task::all());
});

it('allows guest to load the grades list page', function () {
    Task::factory()->count(3)->create();

    $this->actingAs(User::factory()->guest()->create());

    Livewire::test(ListTasks::class)
        ->assertOk()
        ->assertCanSeeTableRecords(Task::all());
});

it('prevents authoriser from loading the grades list page', function () {
    $this->actingAs(User::factory()->authoriser()->create());

    Livewire::test(ListTasks::class)
        ->assertForbidden();
});

it('prevents user from loading the grades list page', function () {
    $this->actingAs(User::factory()->user()->create());

    Livewire::test(ListTasks::class)
        ->assertForbidden();
});
