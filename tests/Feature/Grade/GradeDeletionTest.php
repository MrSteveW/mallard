<?php

use App\Filament\Resources\Grades\Pages\EditGrade;
use App\Models\Grade;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

use function Pest\Laravel\assertDatabaseMissing;

uses(MocksUserObserver::class);

it('allows admin to delete a grade', function () {
    $grade = Grade::factory()->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(EditGrade::class, ['record' => $grade->getKey()])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseMissing('grades', ['id' => $grade->id]);
});

it('prevents authoriser from deleting a grade', function () {
    $grade = Grade::factory()->create();

    $this->actingAs(User::factory()->authoriser()->create());

    Livewire::test(EditGrade::class, ['record' => $grade->getKey()])
        ->assertForbidden();
});

it('prevents user from deleting a grade', function () {
    $grade = Grade::factory()->create();

    $this->actingAs(User::factory()->user()->create());

    Livewire::test(EditGrade::class, ['record' => $grade->getKey()])
        ->assertForbidden();
});

it('prevents guest from deleting a grade', function () {
    $grade = Grade::factory()->create();

    $this->actingAs(User::factory()->guest()->create());

    Livewire::test(EditGrade::class, ['record' => $grade->getKey()])
        ->assertForbidden();
});
