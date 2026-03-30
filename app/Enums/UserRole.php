<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'Admin';
    case Editor = 'Editor';
    case Viewer = 'Viewer';
    case Guest = 'Guest';
}
