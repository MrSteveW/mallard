<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LeaveRequestPolicy
{
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
        return $user->id === $leaveRequest->user_id
        && is_Null($leaveRequest->approved_by)
        && is_Null($leaveRequest->declined_by);
    }

    public function restore(User $user, LeaveRequest $leaveRequest): bool
    {
        return false;
    }

    public function forceDelete(User $user, LeaveRequest $leaveRequest): bool
    {
        return false;
    }
}
