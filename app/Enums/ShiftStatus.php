<?php

namespace App\Enums;

enum ShiftStatus: string
{
    case OnDuty = 'On Duty';
    case Off = 'Off';
    case AnnualLeave = 'Annual Leave';
    case SickLeave = 'Sick leave';
    case Training = 'Training';
    case Unavailable = 'Unavailable';
}
