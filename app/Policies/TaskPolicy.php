<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
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

    public function view(User $user, Task $task): bool
    {
        return $user->role === UserRole::Guest;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Task $task): bool
    {
        return false;
    }

    public function delete(User $user, Task $task): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
