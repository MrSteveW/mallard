<?php

use App\Enums\UserRole;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

use function Pest\Laravel\assertDatabaseHas;

uses(MocksUserObserver::class);

it('allows admin to load the create user page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    Livewire::test(CreateUser::class)
        ->assertOk();
});

it('allows admin to create a user with an employee record', function () {
    $admin = User::factory()->admin()->create();
    $grade = Grade::create(['name' => 'Test Grade']);
    $newUser = User::factory()->make();

    $this->actingAs($admin);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => $newUser->name,
            'email' => $newUser->email,
            'role' => UserRole::User->value,
            'grade_id' => $grade->id,
            'training' => 'Some training notes',
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas('users', [
        'name' => $newUser->name,
        'email' => $newUser->email,
    ]);

    $created = User::where('email', $newUser->email)->first();

    assertDatabaseHas('employees', [
        'user_id' => $created->id,
        'grade_id' => $grade->id,
        'training' => 'Some training notes',
    ]);
});

it('validates the create user form', function (array $data, array $errors) {
    $admin = User::factory()->admin()->create();
    $grade = Grade::create(['name' => 'Test Grade']);
    $newUser = User::factory()->make();

    $this->actingAs($admin);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => $newUser->name,
            'email' => $newUser->email,
            'role' => UserRole::User->value,
            'grade_id' => $grade->id,
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNoRedirect();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`email` is required' => [['email' => null], ['email' => 'required']],
    '`email` must be a valid email' => [['email' => Str::random()], ['email' => 'email']],
    '`grade_id` is required' => [['grade_id' => null], ['grade_id' => 'required']],
    '`role` is required' => [['role' => null], ['role' => 'required']],
]);

it('requires email to be unique when creating a user', function () {
    $admin = User::factory()->admin()->create();
    $existingUser = User::factory()->create();
    $grade = Grade::create(['name' => 'Test Grade']);

    $this->actingAs($admin);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'New User',
            'email' => $existingUser->email,
            'role' => UserRole::User->value,
            'grade_id' => $grade->id,
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'unique']);
});

it('prevents authoriser from accessing the create user page', function () {
    $this->actingAs(User::factory()->authoriser()->create());

    Livewire::test(CreateUser::class)
        ->assertForbidden();
});

it('prevents user from accessing the create user page', function () {
    $this->actingAs(User::factory()->user()->create());

    Livewire::test(CreateUser::class)
        ->assertForbidden();
});

it('prevents guest from creating a user', function () {
    $this->actingAs(User::factory()->guest()->create());

    Livewire::test(CreateUser::class)
        ->assertForbidden();
});
