<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'Admin';
    case Authoriser = 'Authoriser';
    case User = 'User';
    case Guest = 'Guest';
}
