<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;

class BootstrapCommand extends Command
{
    protected $signature = 'app:bootstrap';

    protected $description = 'Bootstrap the initial Admin and Guest system accounts';

    public function handle(): void
    {
        if (! config('services.bootstrapUser.email') || ! config('services.bootstrapUser.password')) {
            $this->error('BOOTSTRAP_EMAIL or BOOTSTRAP_PASSWORD is not set.');

            return;
        }

        if (! config('services.guest.email') || ! config('services.guest.password')) {
            $this->error('GUEST_EMAIL or GUEST_PASSWORD is not set.');

            return;
        }

        User::firstOrCreate(
            ['email' => config('services.bootstrapUser.email')],
            [
                'name' => 'Admin',
                'password' => config('services.bootstrapUser.password'),
                'role' => UserRole::Admin,
            ]
        );

        User::firstOrCreate(
            ['email' => config('services.guest.email')],
            [
                'name' => 'Guest',
                'password' => config('services.guest.password'),
                'role' => UserRole::Guest,
            ]
        );
        $this->call('bank-holidays:backfill');

        $this->info('Bootstrap complete: Admin and Guest accounts are ready.');
    }
}
