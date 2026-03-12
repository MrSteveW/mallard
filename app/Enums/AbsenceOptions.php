<?php

namespace App\Enums;

enum AbsenceOptions: string
{
    case AnnualLeave = 'Annual Leave';
    case Sickness = 'Sickness';
    case Medical = 'Medical';
    case Training = 'Training';
    case CompassionateLeave = 'Compassionate Leave';
    case Other = 'Other';

public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
        ], self::cases());
    }
}