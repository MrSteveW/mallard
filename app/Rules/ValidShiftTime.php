<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Enums\HourEnum;
use App\Enums\MinutesEnum;

class ValidShiftTime implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1. Basic format check
        $parts = explode(':', $value);
        
        if (count($parts) !== 3) {
            $fail("The :attribute must be in HH:MM:SS format.");
            return;
        }

        [$hh, $mm, $ss] = $parts;

        // 2. Validate against Enums
        // tryFrom returns null if the value isn't in the Enum
        if (!HourEnum::tryFrom($hh)) {
            $fail("The hour in :attribute is invalid.");
        }

        if (!MinutesEnum::tryFrom($mm)) {
            $fail("The minutes in :attribute are invalid.");
        }

        // 3. Strict seconds check
        if ($ss !== '00') {
            $fail("The seconds in :attribute must be 00.");
        }
    }
}