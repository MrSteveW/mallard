<?php

use App\Enums\ShiftType;

describe('defaultTimes', function () {
    it('returns null times for Off shift', function () {
        expect(ShiftType::Off->defaultTimes())->toBe([
            'start_time' => null,
            'end_time' => null,
        ]);
    });

    it('returns correct times for each shift type', function (ShiftType $shiftType, string $start, string $end) {
        expect($shiftType->defaultTimes())->toBe([
            'start_time' => $start,
            'end_time' => $end,
        ]);
    })->with([
        'Early' => [ShiftType::Early, '08:00', '16:00'],
        'Late' => [ShiftType::Late,  '12:00', '20:00'],
        'Late2' => [ShiftType::Late2, '14:00', '22:00'],
        'Night' => [ShiftType::Night, '20:00', '08:00'],
    ]);
});

describe('options', function () {
    it('returns an entry for every shift type', function () {
        $values = array_column(ShiftType::options(), 'value');

        foreach (ShiftType::cases() as $case) {
            expect($values)->toContain($case->value);
        }
    });

    it('each option has the required keys', function () {
        foreach (ShiftType::options() as $option) {
            expect($option)->toHaveKeys(['value', 'label', 'start_time', 'end_time']);
        }
    });

    it('each option times match defaultTimes', function () {
        foreach (ShiftType::options() as $option) {
            $case = ShiftType::from($option['value']);
            $expected = $case->defaultTimes();

            expect($option['start_time'])->toBe($expected['start_time'])
                ->and($option['end_time'])->toBe($expected['end_time']);
        }
    });
});
