<?php

use App\Filament\Resources\Tasks\Pages\CreateTask;
use App\Models\Task;
use App\Models\User;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

use function Pest\Laravel\assertDatabaseHas;

uses(MocksUserObserver::class);

it('allows admin to load the create task page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    Livewire::test(CreateTask::class)
        ->assertOk();
});

it('allows admin to create a task', function () {
    $admin = User::factory()->admin()->create();
    $newTask = Task::factory()->make();

    $this->actingAs($admin);

    Livewire::test(CreateTask::class)
        ->fillForm([
            'name' => $newTask->name,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas('tasks', [
        'name' => $newTask->name,
    ]);
});

it('requires name to be present when creating a task', function () {
    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(CreateTask::class)
        ->fillForm(['name' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required'])
        ->assertNoRedirect();
});

it('requires name to be unique when creating a task', function () {
    $existingTask = Task::factory()->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(CreateTask::class)
        ->fillForm(['name' => $existingTask->name])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('prevents authoriser from accessing the create task page', function () {
    $this->actingAs(User::factory()->authoriser()->create());

    Livewire::test(CreateTask::class)
        ->assertForbidden();
});

it('prevents user from accessing the create task page', function () {
    $this->actingAs(User::factory()->user()->create());

    Livewire::test(CreateTask::class)
        ->assertForbidden();
});

it('prevents guest from creating a task', function () {
    $this->actingAs(User::factory()->guest()->create());

    Livewire::test(CreateTask::class)
        ->assertForbidden();
});
