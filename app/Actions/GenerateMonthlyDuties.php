<?php

namespace App\Actions;

use App\Models\Duty;
use App\Models\DutyGenerationRun;
use App\Models\ShiftPattern;
use App\Models\ShiftRepeat;
use Carbon\Carbon;

class GenerateMonthlyDuties
{
    public function handle(Carbon $month, ?string $triggeredBy = 'schedule', ?int $userId = null)
    {
        $shiftRepeat = ShiftRepeat::sole();
        $totalDays = $shiftRepeat->total_days;
        $startDate = $shiftRepeat->shift_pattern_start_date;

        $daysSinceAnchor = (int) $startDate->diffInDays($month->copy()->startOfMonth());

        // collection of ShiftPatternDay numbers for the month April is 66, 67...
        $patternDaysInMonth = collect(range(0, $month->daysInMonth - 1))
            ->map(fn ($i) => (($daysSinceAnchor + $i) % $totalDays) + 1);

        // {"66": [{ShifPattern},{}...], "67":[]}
        $shiftsForMonth = ShiftPattern::whereIn('day', $patternDaysInMonth)
            ->where('shift_type', '!=', 'Off')
            ->get()
            ->groupBy('day');

        for ($i = 0; $i < $month->daysInMonth; $i++) {
            $calendarDate = $month->copy()->addDays($i)->toDateString();
            $shiftsForDay = $shiftsForMonth->get($patternDaysInMonth[$i], collect());

            foreach ($shiftsForDay as $shift) {
                $start = Carbon::createFromFormat('H:i', substr($shift->start_time, 0, 5));
                $end = Carbon::createFromFormat('H:i', substr($shift->end_time, 0, 5));
                if ($end->lessThanOrEqualTo($start)) {
                    $end->addDay();
                }
                $duration = $start->diffInMinutes($end);

                Duty::firstOrCreate(['user_id' => $shift->user_id, 'date' => $calendarDate],
                    [
                        'shift_type' => $shift->shift_type,
                        'start_time' => $shift->start_time,
                        'end_time' => $shift->end_time,
                        'duration' => $duration,
                        'generated_from_pattern_day' => $patternDaysInMonth[$i],
                    ],
                );

            }
        }
        DutyGenerationRun::firstorCreate(['year_month' => $month->format('Y-m')],
            ['triggered_by' => $triggeredBy,
                'user_id' => $userId,
                'created_at' => now()]);
    }
}
