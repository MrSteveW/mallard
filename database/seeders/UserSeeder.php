<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Enums\UserRole;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
      User::create([
            'name' => 'Adam',
            'email' => 'adam@example.com',
            'password' => 'password',
            'role' => UserRole::Admin,
            ]);
      User::create([
         'name' => 'Betty',
         'email' => 'betty@example.com',
         'password' => 'password',
         'role' => UserRole::Admin,
         ]);

      User::create([
         'name' => 'Claire',
         'email' => 'claire@example.com',
         'password' => 'password',
         'role' => UserRole::Editor,
         ]);

      User::create([
         'name' => 'Declan',
         'email' => 'declan@example.com',
         'password' => 'password',
         'role' => UserRole::Viewer,
         ]);

      User::create([
      'name' => 'Ewan',
      'email' => 'ewan@example.com',
      'password' => 'password',
      'role' => UserRole::Editor,
      ]);

      User::create([
      'name' => 'Francine',
      'email' => 'francine@example.com',
      'password' => 'password',
      'role' => UserRole::Viewer,
      ]);

      User::create([
      'name' => 'Gary',
      'email' => 'gary@example.com',
      'password' => 'password',
      'role' => UserRole::Viewer,
      ]);

      User::create([
      'name' => 'Hilda',
      'email' => 'hilda@example.com',
      'password' => 'password',
      'role' => UserRole::Viewer,
      ]);

      User::create([
      'name' => 'Iain',
      'email' => 'iain@example.com',
      'password' => 'password',
      'role' => UserRole::Editor,
      ]);

      User::create([
      'name' => 'Jane',
      'email' => 'jane@example.com',
      'password' => 'password',
      'role' => UserRole::Viewer,
      ]);

      User::create([
      'name' => 'Kier',
      'email' => 'kier@example.com',
      'password' => 'password',
      'role' => UserRole::Viewer,
      ]);

      User::create([
      'name' => 'Lisa',
      'email' => 'lisa@example.com',
      'password' => 'password',
      'role' => UserRole::Viewer,
      ]);

      User::create([
      'name' => 'Mika',
      'email' => 'mika@example.com',
      'password' => 'password',
      'role' => UserRole::Viewer,
      ]);

      User::create([
      'name' => 'Narissa',
      'email' => 'narissa@example.com',
      'password' => 'password',
      'role' => UserRole::Viewer,
      ]);

      User::create([
         'name' => 'Guest',
         'email' => 'guest@example.com',
         'password' => 'guestpassword',
         'role' => UserRole::Guest,
      ]);

      // User::create([
      // 'name' => 'Odette',
      // 'email' => 'odette@example.com',
      // 'password' => 'password',
      // 'role' => UserRole::Viewer,
      // ]);

      // User::create([
      // 'name' => 'Paul',
      // 'email' => 'paul@example.com',
      // 'password' => 'password',
      // 'role' => UserRole::Viewer,
      // ]);

      // User::create([
      // 'name' => 'Quentin',
      // 'email' => 'quentin@example.com',
      // 'password' => 'password',
      // 'role' => UserRole::Viewer,
      // ]);

      // User::create([
      // 'name' => 'Ruby',
      // 'email' => 'ruby@example.com',
      // 'password' => 'password',
      // 'role' => UserRole::Viewer,
      // ]);

      // User::create([
      // 'name' => 'Seb',
      // 'email' => 'seb@example.com',
      // 'password' => 'password',
      // 'role' => UserRole::Viewer,
      // ]);

      // User::create([
      // 'name' => 'Tabitha',
      // 'email' => 'tabitha@example.com',
      // 'password' => 'password',
      // 'role' => UserRole::Viewer,
      // ]);
    }
}
