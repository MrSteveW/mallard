<?php

use App\Filament\Resources\Tasks\Pages\EditTask;
use App\Models\Task;
use App\Models\User;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

use function Pest\Laravel\assertDatabaseHas;

uses(MocksUserObserver::class);

it('allows admin to load the edit task page', function () {
    $task = Task::factory()->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(EditTask::class, ['record' => $task->getKey()])
        ->assertOk()
        ->assertSchemaStateSet([
            'name' => $task->name,
        ]);
});

it('allows admin to update a task', function () {
    $task = Task::factory()->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(EditTask::class, ['record' => $task->getKey()])
        ->fillForm([
            'name' => 'Updated Task',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas('tasks', [
        'id' => $task->id,
        'name' => 'Updated Task',
    ]);
});

it('requires name when updating a task', function () {
    $task = Task::factory()->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(EditTask::class, ['record' => $task->getKey()])
        ->fillForm(['name' => null])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

it('requires name to be unique when updating a task', function () {
    $task = Task::factory()->create();
    $otherTask = Task::factory()->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(EditTask::class, ['record' => $task->getKey()])
        ->fillForm(['name' => $otherTask->name])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('prevents authoriser from editing a task', function () {
    $task = Task::factory()->create();

    $this->actingAs(User::factory()->authoriser()->create());

    Livewire::test(EditTask::class, ['record' => $task->getKey()])
        ->assertForbidden();
});

it('prevents user from editing a task', function () {
    $task = Task::factory()->create();

    $this->actingAs(User::factory()->user()->create());

    Livewire::test(EditTask::class, ['record' => $task->getKey()])
        ->assertForbidden();
});

it('prevents guest from editing a task', function () {
    $task = Task::factory()->create();

    $this->actingAs(User::factory()->guest()->create());

    Livewire::test(EditTask::class, ['record' => $task->getKey()])
        ->assertForbidden();
});
