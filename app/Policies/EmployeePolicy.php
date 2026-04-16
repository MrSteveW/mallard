<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class EmployeePolicy
{
    public function before(User $user): ?bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }
}
