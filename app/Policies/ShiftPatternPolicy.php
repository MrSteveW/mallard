<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\ShiftPattern;
use App\Models\User;

class ShiftPatternPolicy
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
        return $user->role === UserRole::Guest;
    }

    public function view(User $user, ?ShiftPattern $shiftPattern = null): bool
    {
        return $user->role === UserRole::Guest;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ?ShiftPattern $shiftPattern = null): bool
    {
        return false;
    }

    public function delete(User $user, ShiftPattern $shiftPattern): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
