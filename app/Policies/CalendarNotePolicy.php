<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\CalendarNote;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CalendarNotePolicy
{

    public function before(User $user): ?bool
    {
        if (in_array($user->role, [UserRole::Admin, UserRole::Authoriser])) {
            return true;
        }

        return null;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, CalendarNote $calendarNote): bool
    {
        return false;
    }

    public function delete(User $user, CalendarNote $calendarNote): bool
    {
        return false;
    }
}
