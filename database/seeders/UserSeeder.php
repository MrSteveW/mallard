<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'name' => 'Adam',
            'email' => config('services.bootstrapUser.email'),
            'password' => config('services.bootstrapUser.password'),
            'role' => UserRole::Admin,
        ]);

        User::create([
            'name' => 'Betty',
            'email' => 'betty@example.com',
            'password' => 'password',
            'role' => UserRole::Authoriser,
        ]);

        User::create([
            'name' => 'Claire',
            'email' => 'claire@example.com',
            'password' => 'password',
            'role' => UserRole::User,
        ]);

        User::create([
            'name' => 'Declan',
            'email' => 'declan@example.com',
            'password' => 'password',
            'role' => UserRole::User,
        ]);

        User::create([
            'name' => 'Ewan',
            'email' => 'ewan@example.com',
            'password' => 'password',
            'role' => UserRole::User,
        ]);

        User::create([
            'name' => 'Francine',
            'email' => 'francine@example.com',
            'password' => 'password',
            'role' => UserRole::User,
        ]);

        User::create([
            'name' => 'Gary',
            'email' => 'gary@example.com',
            'password' => 'password',
            'role' => UserRole::User,
        ]);

        User::create([
            'name' => 'Hilda',
            'email' => 'hilda@example.com',
            'password' => 'password',
            'role' => UserRole::User,
        ]);

        User::create([
            'name' => 'Iain',
            'email' => 'iain@example.com',
            'password' => 'password',
            'role' => UserRole::User,
        ]);

        User::create([
            'name' => 'Jane',
            'email' => 'jane@example.com',
            'password' => 'password',
            'role' => UserRole::User,
        ]);

        User::create([
            'name' => 'Kier',
            'email' => 'kier@example.com',
            'password' => 'password',
            'role' => UserRole::User,
        ]);

        User::create([
            'name' => 'Lisa',
            'email' => 'lisa@example.com',
            'password' => 'password',
            'role' => UserRole::User,
        ]);

        User::create([
            'name' => 'Guest',
            'email' => config('services.guest.email'),
            'password' => config('services.guest.password'),
            'role' => UserRole::Guest,
        ]);

        // User::create([
        // 'name' => 'Mika',
        // 'email' => 'mika@example.com',
        // 'password' => 'password',
        // 'role' => UserRole::User,
        // ]);

        // User::create([
        // 'name' => 'Narissa',
        // 'email' => 'narissa@example.com',
        // 'password' => 'password',
        // 'role' => UserRole::User,
        // ]);

        // User::create([
        // 'name' => 'Odette',
        // 'email' => 'odette@example.com',
        // 'password' => 'password',
        // 'role' => UserRole::User,
        // ]);

        // User::create([
        // 'name' => 'Paul',
        // 'email' => 'paul@example.com',
        // 'password' => 'password',
        // 'role' => UserRole::User,
        // ]);

        // User::create([
        // 'name' => 'Quentin',
        // 'email' => 'quentin@example.com',
        // 'password' => 'password',
        // 'role' => UserRole::User,
        // ]);

        // User::create([
        // 'name' => 'Ruby',
        // 'email' => 'ruby@example.com',
        // 'password' => 'password',
        // 'role' => UserRole::User,
        // ]);

        // User::create([
        // 'name' => 'Seb',
        // 'email' => 'seb@example.com',
        // 'password' => 'password',
        // 'role' => UserRole::User,
        // ]);

        // User::create([
        // 'name' => 'Tabitha',
        // 'email' => 'tabitha@example.com',
        // 'password' => 'password',
        // 'role' => UserRole::User,
        // ]);
    }
}
