<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class DutyPolicy
{
    public function before(User $user): ?bool
    {
      if (in_array($user->role, [UserRole::Admin, UserRole::Authoriser, UserRole::Guest])) {
          return true;
      }
      return null;
    }
    
    public function viewAny(User $user): bool
    {
      return false;
    }
}
