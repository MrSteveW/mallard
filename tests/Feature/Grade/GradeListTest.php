<?php

use App\Filament\Resources\Grades\Pages\ListGrades;
use App\Models\Grade;
use App\Models\User;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

it('allows admin to load the grades list page', function () {
    $grades = Grade::factory()->count(3)->sequence(
        ['name' => 'Band 1'],
        ['name' => 'Band 2'],
        ['name' => 'Band 3'],
    )->create();

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(ListGrades::class)
        ->assertOk()
        ->assertCanSeeTableRecords($grades);
});

it('allows guest to load the grades list page', function () {
    $grades = Grade::factory()->count(3)->sequence(
        ['name' => 'Band 1'],
        ['name' => 'Band 2'],
        ['name' => 'Band 3'],
    )->create();

    $this->actingAs(User::factory()->guest()->create());

    Livewire::test(ListGrades::class)
        ->assertOk()
        ->assertCanSeeTableRecords($grades);
});

it('prevents authoriser from loading the grades list page', function () {
    $this->actingAs(User::factory()->authoriser()->create());

    Livewire::test(ListGrades::class)
        ->assertForbidden();
});

it('prevents user from loading the grades list page', function () {
    $this->actingAs(User::factory()->user()->create());

    Livewire::test(ListGrades::class)
        ->assertForbidden();
});
