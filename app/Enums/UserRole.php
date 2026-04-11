<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'Admin';
    case Authoriser = 'Authoriser';
    case Viewer = 'Viewer';
    case Guest = 'Guest';
}
