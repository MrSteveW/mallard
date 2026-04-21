<?php

namespace App\Enums;

enum HourEnum: string
{
    case H00 = '00';
    case H01 = '01';
    case H02 = '02';
    case H03 = '03';
    case H04 = '04';
    case H05 = '05';
    case H06 = '06';
    case H07 = '07';
    case H08 = '08';
    case H09 = '09';
    case H10 = '10';
    case H11 = '11';
    case H12 = '12';
    case H13 = '13';
    case H14 = '14';
    case H15 = '15';
    case H16 = '16';
    case H17 = '17';
    case H18 = '18';
    case H19 = '19';
    case H20 = '20';
    case H21 = '21';
    case H22 = '22';
    case H23 = '23';

    public static function options(): array
    {
        return array_map(function ($hour) {
            $formatted = str_pad((string) $hour, 2, '0', STR_PAD_LEFT);

            return [
                'value' => $formatted,
                'label' => $formatted,
            ];
        }, range(0, 23));
    }
}
