<?php

namespace App\Enums;

enum ShiftType: string
{
    case Off = 'Off';
    case Early = 'Early';
    case Late = 'Late';
    case Late2 = 'Late2';
    case Night = 'Night';

    public function defaultTimes(): array
    {
        return match ($this) {
            self::Off => ['start_time' => null, 'end_time' => null],
            self::Early => ['start_time' => '08:00', 'end_time' => '16:00'],
            self::Late => ['start_time' => '12:00', 'end_time' => '20:00'],
            self::Late2 => ['start_time' => '14:00', 'end_time' => '22:00'],
            self::Night => ['start_time' => '20:00', 'end_time' => '08:00'],
        };
    }

    public static function options(): array
    {
        return array_map(fn ($case) => [
            'value' => $case->value,
            'label' => $case->value,
            'start_time' => $case->defaultTimes()['start_time'],
            'end_time' => $case->defaultTimes()['end_time'],
        ], self::cases());
    }
}
