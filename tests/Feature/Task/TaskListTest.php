<?php

use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Models\Task;
use App\Models\User;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

it('allows admin to load the task list page', function () {
    $tasks = Task::factory()->count(3)->sequence(
        ['name' => 'Task A'],
        ['name' => 'Task B'],
        ['name' => 'Task C'],
    )->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(ListTasks::class)
        ->assertOk()
        ->assertCanSeeTableRecords($tasks);
});

it('allows guest to load the task list page', function () {
    $tasks = Task::factory()->count(3)->sequence(
        ['name' => 'Task A'],
        ['name' => 'Task B'],
        ['name' => 'Task C'],
    )->create();

    $this->actingAs(User::factory()->guest()->create());

    Livewire::test(ListTasks::class)
        ->assertOk()
        ->assertCanSeeTableRecords($tasks);
});

it('prevents authoriser from loading the task list page', function () {
    $this->actingAs(User::factory()->authoriser()->create());

    Livewire::test(ListTasks::class)
        ->assertForbidden();
});

it('prevents user from loading the task list page', function () {
    $this->actingAs(User::factory()->user()->create());

    Livewire::test(ListTasks::class)
        ->assertForbidden();
});
