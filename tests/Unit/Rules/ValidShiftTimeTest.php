<?php

use App\Rules\ValidShiftTime;

function validateShiftTime(string $value): array
{
    $errors = [];
    (new ValidShiftTime)->validate('start_time', $value, function (string $message) use (&$errors) {
        $errors[] = str_replace(':attribute', 'start_time', $message);
    });

    return $errors;
}

describe('valid times', function () {
    it('passes for a standard daytime shift time', function () {
        expect(validateShiftTime('09:00:00'))->toBeEmpty();
    });

    it('passes for midnight', function () {
        expect(validateShiftTime('00:00:00'))->toBeEmpty();
    });

    it('passes for the last valid hour', function () {
        expect(validateShiftTime('23:00:00'))->toBeEmpty();
    });

    it('passes for quarter-hour minute increments', function () {
        expect(validateShiftTime('09:15:00'))->toBeEmpty();
        expect(validateShiftTime('09:30:00'))->toBeEmpty();
        expect(validateShiftTime('09:45:00'))->toBeEmpty();
    });
});

describe('format validation', function () {
    it('fails when only HH:MM is provided', function () {
        expect(validateShiftTime('09:00'))->toContain('The start_time must be in HH:MM:SS format.');
    });

    it('fails when four segments are provided', function () {
        expect(validateShiftTime('09:00:00:00'))->toContain('The start_time must be in HH:MM:SS format.');
    });

    it('stops further validation after a format failure', function () {
        $errors = validateShiftTime('invalid');
        expect($errors)->toHaveCount(1);
    });
});

describe('hour validation', function () {
    it('fails for hour 24', function () {
        expect(validateShiftTime('24:00:00'))->toContain('The hour in start_time is invalid.');
    });

    it('fails for a non-zero-padded hour', function () {
        expect(validateShiftTime('9:00:00'))->toContain('The hour in start_time is invalid.');
    });

    it('fails for a non-numeric hour', function () {
        expect(validateShiftTime('ab:00:00'))->toContain('The hour in start_time is invalid.');
    });
});

describe('minutes validation', function () {
    it('fails for minutes not on a quarter-hour', function () {
        expect(validateShiftTime('09:01:00'))->toContain('The minutes in start_time are invalid.');
    });

    it('fails for minutes 60', function () {
        expect(validateShiftTime('09:60:00'))->toContain('The minutes in start_time are invalid.');
    });

    it('fails for non-zero-padded minutes', function () {
        expect(validateShiftTime('09:5:00'))->toContain('The minutes in start_time are invalid.');
    });
});

describe('seconds validation', function () {
    it('fails when seconds are not 00', function () {
        expect(validateShiftTime('09:00:01'))->toContain('The seconds in start_time must be 00.');
    });

    it('fails when seconds are 59', function () {
        expect(validateShiftTime('09:00:59'))->toContain('The seconds in start_time must be 00.');
    });
});

describe('multiple failures', function () {
    it('reports both invalid hour and invalid minutes independently', function () {
        $errors = validateShiftTime('99:99:00');
        expect($errors)->toContain('The hour in start_time is invalid.');
        expect($errors)->toContain('The minutes in start_time are invalid.');
    });

    it('reports invalid hour, minutes, and seconds together', function () {
        $errors = validateShiftTime('99:99:59');
        expect($errors)->toContain('The hour in start_time is invalid.');
        expect($errors)->toContain('The minutes in start_time are invalid.');
        expect($errors)->toContain('The seconds in start_time must be 00.');
    });
});
