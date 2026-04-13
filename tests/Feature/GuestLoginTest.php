<?php

use App\Models\User;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

 test('guest user can authenticate using guest credentials', function () {
      $email = config('services.guest.email');
      $password = config('services.guest.password');
      
      expect($email)->not->toBeNull('GUEST_EMAIL is not set in config/environment')
        ->and($password)->not->toBeNull('GUEST_PASSWORD is not set in config/environment');

      User::factory()->guest()->create([
        'email' => $email,
        'password' => $password,
      ]);

      $response = $this->post(route('login.store', [
        'email' => $email,
        'password' => $password,
      ]));

      $this->assertAuthenticated();
      $response->assertRedirect(route('dashboard', absolute: false));
  });
