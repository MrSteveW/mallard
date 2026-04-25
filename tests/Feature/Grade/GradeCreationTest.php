<?php

use App\Filament\Resources\Grades\Pages\CreateGrade;
use App\Models\Grade;
use App\Models\User;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

use function Pest\Laravel\assertDatabaseHas;

uses(MocksUserObserver::class);

it('allows admin to load the create grade page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    Livewire::test(CreateGrade::class)
        ->assertOk();
});

it('allows admin to create a grade', function () {
    $admin = User::factory()->admin()->create();
    $newGrade = Grade::factory()->make();

    $this->actingAs($admin);

    Livewire::test(CreateGrade::class)
        ->fillForm([
            'name' => $newGrade->name,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas('grades', [
        'name' => $newGrade->name,
    ]);
});

it('requires name to be present when creating a grade', function () {
    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(CreateGrade::class)
        ->fillForm(['name' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required'])
        ->assertNoRedirect();
});

it('requires name to be unique when creating a grade', function () {
    $existingGrade = Grade::factory()->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(CreateGrade::class)
        ->fillForm(['name' => $existingGrade->name])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('prevents authoriser from accessing the create grade page', function () {
    $this->actingAs(User::factory()->authoriser()->create());

    Livewire::test(CreateGrade::class)
        ->assertForbidden();
});

it('prevents user from accessing the create grade page', function () {
    $this->actingAs(User::factory()->user()->create());

    Livewire::test(CreateGrade::class)
        ->assertForbidden();
});

it('prevents guest from creating a grade', function () {
    $this->actingAs(User::factory()->guest()->create());

    Livewire::test(CreateGrade::class)
        ->assertForbidden();
});
