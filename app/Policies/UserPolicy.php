<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function before(User $user): bool|null
    {
        if ($user->role===UserRole::Admin) {
            return true;
        }
        elseif ($user->role===UserRole::Guest) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }
}
