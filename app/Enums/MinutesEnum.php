<?php

namespace App\Enums;

enum MinutesEnum: string {

  public static function options(): array {
    return array_map(fn($m) => [
        'value' => str_pad($m, 2, '0', STR_PAD_LEFT),
        'label' => str_pad($m, 2, '0', STR_PAD_LEFT)
        ], [0, 15, 30, 45]);
    }
}