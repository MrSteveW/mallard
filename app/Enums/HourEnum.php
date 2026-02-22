<?php

namespace App\Enums;

enum HourEnum: string {

    public static function options(): array {
        return array_map(function($hour) {
            $formatted = str_pad($hour, 2, '0', STR_PAD_LEFT);
            return [
                'value' => $formatted,
                'label' => $formatted,
            ];
        }, range(0, 23));
    }
}