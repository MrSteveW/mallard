<?php

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Mail\UserCreated;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

it('queues UserCreated mail when admin creates a user via Filament', function () {
    Mail::fake();

    $admin = User::factory()->admin()->create();
    $grade = Grade::create(['name' => 'Test Grade']);

    $this->actingAs($admin);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'role' => 'User',
            'grade_id' => $grade->id,
            'training' => null,
        ])
        ->call('create')
        ->assertHasNoErrors();

    Mail::assertQueued(UserCreated::class, function ($mail) {
        return $mail->user->email === 'jane@example.com'
            && str_contains($mail->resetUrl, 'reset-password');
    });
});
