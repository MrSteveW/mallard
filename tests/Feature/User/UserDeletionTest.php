<?php

use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

use function Pest\Laravel\assertDatabaseMissing;

uses(MocksUserObserver::class);

it('allows admin to delete a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin);

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseMissing('users', ['id' => $user->id]);
});

it('prevents authoriser from deleting a user', function () {
    $user = User::factory()->create();

    $this->actingAs(User::factory()->authoriser()->create());

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->assertForbidden();
});

it('prevents user from deleting a user', function () {
    $user = User::factory()->create();

    $this->actingAs(User::factory()->user()->create());

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->assertForbidden();
});

it('prevents guest from deleting a user', function () {
    $user = User::factory()->create();

    $this->actingAs(User::factory()->guest()->create());

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->assertForbidden();
});
