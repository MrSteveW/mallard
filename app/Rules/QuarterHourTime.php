<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class QuarterHourTime implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^\d{2}:\d{2}$/', $value)) {
            $fail('The :attribute must be a valid time in HH:mm format.');

            return;
        }
        [$hour, $minute] = explode(':', $value);
        if ((int) $hour > 23 || ! in_array($minute, ['00', '15', '30', '45'])) {
            $fail('The :attribute must be on a 15-minute interval (00, 15, 30, 45).');
        }
    }
}
