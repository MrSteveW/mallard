<?php

namespace App\Enums;

enum LeaveOptions: string
{
    case AnnualLeave = 'Annual Leave';
    case Sickness = 'Sickness';
    case Medical = 'Medical';
    case Training = 'Training';
    case Bereavement = 'Bereavement';
    case MaternityLeave = 'Maternity Leave';
    case PaternityLeave = 'Paternity Leave';
    case ParentalLeave = 'Parental Leave';
    case CompassionateLeave = 'Compassionate Leave';
    case IndustrialAction = 'Industrial Action';
    
    case Other = 'Other';

    public static function options(): array
    {
        return array_map(fn ($case) => [
            'value' => $case->value,
        ], self::cases());
    }
}
