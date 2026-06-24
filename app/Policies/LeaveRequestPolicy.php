<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy
{
    public function manage(User $user): bool
    {
        if (in_array($user->role, [UserRole::Admin, UserRole::Authoriser, UserRole::Guest])) {
            return true;
        }

        return false;
    }

    public function approve(User $user): bool
    {
        if (in_array($user->role, [UserRole::Admin, UserRole::Authoriser])) {
            return true;
        }

        return false;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        if (in_array($user->role, [UserRole::Admin, UserRole::Authoriser, UserRole::User])) {
            return true;
        }

        return false;
    }

    public function update(User $user, LeaveRequest $leaveRequest): bool
    {
        return false;
    }

    public function delete(User $user, LeaveRequest $leaveRequest): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Authoriser, UserRole::User])
        && $user->id == $leaveRequest->user_id
        && is_null($leaveRequest->approved_by)
        && is_null($leaveRequest->declined_by);
    }
}
