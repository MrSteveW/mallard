 <?php

  use App\Mail\UserCreated;
  use App\Models\User;
  use Illuminate\Support\Facades\Mail;
  use Tests\Traits\MocksUserObserver;

  uses(MocksUserObserver::class);

  test('queues UserCreated mail when admin creates a user', function () {
      Mail::fake();

      $admin = User::factory()->admin()->create();

      $this->actingAs($admin)
          ->post(route('users.store'), [
              'name' => 'Jane Doe',
              'email' => 'jane@example.com',
              'password' => 'password',
              'role' => 'User',
              'grade_id' => 1,
              'training' => null,
          ])
          ->assertRedirect('/users');

      Mail::assertQueued(UserCreated::class, function ($mail) {
          return $mail->user->email === 'jane@example.com';
      });
  });