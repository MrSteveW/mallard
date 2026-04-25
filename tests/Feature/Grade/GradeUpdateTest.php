<?php

use App\Filament\Resources\Grades\Pages\EditGrade;
use App\Models\Grade;
use App\Models\User;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

use function Pest\Laravel\assertDatabaseHas;

uses(MocksUserObserver::class);

it('allows admin to load the edit grade page', function () {
    $grade = Grade::factory()->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(EditGrade::class, ['record' => $grade->getKey()])
        ->assertOk()
        ->assertSchemaStateSet([
            'name' => $grade->name,
        ]);
});

it('allows admin to update a grade', function () {
    $grade = Grade::factory()->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(EditGrade::class, ['record' => $grade->getKey()])
        ->fillForm([
            'name' => 'Updated grade',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas('grades', [
        'id' => $grade->id,
        'name' => 'Updated grade',
    ]);
});

it('requires name when updating a grade', function () {
    $grade = Grade::factory()->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(EditGrade::class, ['record' => $grade->getKey()])
        ->fillForm(['name' => null])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

it('requires name to be unique when updating a grade', function () {
    $grade = Grade::factory()->create();
    $othergrade = Grade::factory()->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(EditGrade::class, ['record' => $grade->getKey()])
        ->fillForm(['name' => $othergrade->name])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('prevents authoriser from editing a grade', function () {
    $grade = Grade::factory()->create();

    $this->actingAs(User::factory()->authoriser()->create());

    Livewire::test(EditGrade::class, ['record' => $grade->getKey()])
        ->assertForbidden();
});

it('prevents user from editing a grade', function () {
    $grade = Grade::factory()->create();

    $this->actingAs(User::factory()->user()->create());

    Livewire::test(EditGrade::class, ['record' => $grade->getKey()])
        ->assertForbidden();
});

it('prevents guest from editing a grade', function () {
    $grade = Grade::factory()->create();

    $this->actingAs(User::factory()->guest()->create());

    Livewire::test(EditGrade::class, ['record' => $grade->getKey()])
        ->assertForbidden();
});
