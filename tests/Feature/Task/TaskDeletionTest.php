<?php

use App\Filament\Resources\Tasks\Pages\EditTask;
use App\Models\Task;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

use function Pest\Laravel\assertDatabaseMissing;

uses(MocksUserObserver::class);

it('allows admin to delete a task', function () {
    $task = Task::factory()->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(EditTask::class, ['record' => $task->getKey()])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseMissing('tasks', ['id' => $task->id]);
});

it('prevents authoriser from deleting a task', function () {
    $task = Task::factory()->create();

    $this->actingAs(User::factory()->authoriser()->create());

    Livewire::test(EditTask::class, ['record' => $task->getKey()])
        ->assertForbidden();
});

it('prevents user from deleting a task', function () {
    $task = Task::factory()->create();

    $this->actingAs(User::factory()->user()->create());

    Livewire::test(EditTask::class, ['record' => $task->getKey()])
        ->assertForbidden();
});

it('prevents guest from deleting a task', function () {
    $task = Task::factory()->create();

    $this->actingAs(User::factory()->guest()->create());

    Livewire::test(EditTask::class, ['record' => $task->getKey()])
        ->assertForbidden();
});
