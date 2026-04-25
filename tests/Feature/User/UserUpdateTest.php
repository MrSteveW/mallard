<?php

use App\Enums\UserRole;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\Grade;
use App\Models\User;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

use function Pest\Laravel\assertDatabaseHas;

uses(MocksUserObserver::class);

it('allows admin to load the edit user page', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin);

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->assertOk()
        ->assertSchemaStateSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
});

it('allows admin to update a user and their employee record', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $grade = Grade::create(['name' => 'Senior']);

    $user->employee()->create(['grade_id' => $grade->id, 'training' => null]);

    $this->actingAs($admin);

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm([
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => UserRole::Authoriser->value,
            'grade_id' => $grade->id,
            'training' => 'Updated training',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);

    assertDatabaseHas('employees', [
        'user_id' => $user->id,
        'grade_id' => $grade->id,
        'training' => 'Updated training',
    ]);
});

it('requires name when updating a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin);

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm(['name' => null])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required']);
});

it('requires email when updating a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin);

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm(['email' => null])
        ->call('save')
        ->assertHasFormErrors(['email' => 'required']);
});

it('requires a valid email when updating a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin);

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm(['email' => 'not-an-email'])
        ->call('save')
        ->assertHasFormErrors(['email' => 'email']);
});

it('requires email to be unique when updating a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $this->actingAs($admin);

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm(['email' => $otherUser->email])
        ->call('save')
        ->assertHasFormErrors(['email' => 'unique']);
});

it('requires role when updating a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin);

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm(['role' => null])
        ->call('save')
        ->assertHasFormErrors(['role' => 'required']);
});

it('requires grade when updating a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $grade = Grade::create(['name' => 'Test Grade']);

    $user->employee()->create(['grade_id' => $grade->id, 'training' => null]);

    $this->actingAs($admin);

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm(['grade_id' => null])
        ->call('save')
        ->assertHasFormErrors(['grade_id' => 'required']);
});

it('does not change the password when left blank on edit', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $grade = Grade::create(['name' => 'Test Grade']);

    $user->employee()->create(['grade_id' => $grade->id, 'training' => null]);

    $originalHash = $user->password;

    $this->actingAs($admin);

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm([
            'name' => $user->name,
            'email' => $user->email,
            'role' => UserRole::User->value,
            'grade_id' => $grade->id,
            'password' => null,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->fresh()->password)->toBe($originalHash);
});

it('prevents authoriser from editing a user', function () {
    $user = User::factory()->create();

    $this->actingAs(User::factory()->authoriser()->create());

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->assertForbidden();
});

it('prevents user from editing a user', function () {
    $user = User::factory()->create();

    $this->actingAs(User::factory()->user()->create());

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->assertForbidden();
});

it('prevents guest from editing a user', function () {
    $user = User::factory()->create();

    $this->actingAs(User::factory()->guest()->create());

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->assertForbidden();
});
