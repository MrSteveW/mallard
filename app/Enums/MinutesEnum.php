<?php

namespace App\Enums;

enum MinutesEnum: string
{
    case M00 = '00';
    case M15 = '15';
    case M30 = '30';
    case M45 = '45';

    public static function options(): array
    {
        return array_map(fn ($m) => [
            'value' => str_pad((string) $m, 2, '0', STR_PAD_LEFT),
            'label' => str_pad((string) $m, 2, '0', STR_PAD_LEFT),
        ], [0, 15, 30, 45]);
    }
}
