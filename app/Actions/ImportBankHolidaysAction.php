<?php

namespace App\Actions;

use App\Models\CalendarNote;
use App\Services\BankHolidayService;

class ImportBankHolidaysAction
{
    public function __construct(private BankHolidayService $bankHolidayService) {}

    public function handle(int $year): int
    {
        $bankHolidays = $this->bankHolidayService->getNormalizedBankHolidays();

        $yearCollection = collect($bankHolidays)->filter(fn ($bankHoliday) => str_starts_with($bankHoliday['date'], (string) $year));

        $count = 0;

        foreach ($yearCollection as $bankHoliday) {
            CalendarNote::updateOrCreate(
                [
                    'date' => $bankHoliday['date'],
                    'source' => 'bank_holiday',
                ],
                [
                    'note' => $bankHoliday['title'],
                ]
            );

            $count++;
        }

        return $count;
    }
}
