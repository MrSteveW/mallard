<?php

use App\Actions\ImportBankHolidaysAction;
use App\Models\CalendarNote;
use App\Services\BankHolidayService;

it('creates CalendarNotes for bank holidays in the requested year', function () {
    $this->mock(BankHolidayService::class)
        ->shouldReceive('getNormalizedBankHolidays')
        ->andReturn([
            ['date' => '2024-01-01', 'title' => 'Bank Holiday: New Year\'s Day'],
            ['date' => '2024-03-29', 'title' => 'Bank Holiday: Good Friday'],
        ]);

    app(ImportBankHolidaysAction::class)->handle(2024);

    $this->assertDatabaseHas('calendar_notes', ['date' => '2024-01-01', 'source' => 'bank_holiday']);
    $this->assertDatabaseHas('calendar_notes', ['date' => '2024-03-29', 'source' => 'bank_holiday']);
});

it('stores the holiday title as the note', function () {
    $this->mock(BankHolidayService::class)
        ->shouldReceive('getNormalizedBankHolidays')
        ->andReturn([
            ['date' => '2024-01-01', 'title' => 'Bank Holiday: New Year\'s Day'],
        ]);

    app(ImportBankHolidaysAction::class)->handle(2024);

    $this->assertDatabaseHas('calendar_notes', [
        'date' => '2024-01-01',
        'note' => 'Bank Holiday: New Year\'s Day',
    ]);
});

it('only imports holidays for the requested year', function () {
    $this->mock(BankHolidayService::class)
        ->shouldReceive('getNormalizedBankHolidays')
        ->andReturn([
            ['date' => '2023-12-26', 'title' => 'Bank Holiday: Boxing Day'],
            ['date' => '2024-01-01', 'title' => 'Bank Holiday: New Year\'s Day'],
            ['date' => '2025-01-01', 'title' => 'Bank Holiday: New Year\'s Day'],
        ]);

    app(ImportBankHolidaysAction::class)->handle(2024);

    $this->assertDatabaseMissing('calendar_notes', ['date' => '2023-12-26']);
    $this->assertDatabaseHas('calendar_notes', ['date' => '2024-01-01']);
    $this->assertDatabaseMissing('calendar_notes', ['date' => '2025-01-01']);
});

it('returns the count of imported holidays', function () {
    $this->mock(BankHolidayService::class)
        ->shouldReceive('getNormalizedBankHolidays')
        ->andReturn([
            ['date' => '2024-01-01', 'title' => 'Bank Holiday: New Year\'s Day'],
            ['date' => '2024-03-29', 'title' => 'Bank Holiday: Good Friday'],
            ['date' => '2024-04-01', 'title' => 'Bank Holiday: Easter Monday'],
        ]);

    $count = app(ImportBankHolidaysAction::class)->handle(2024);

    expect($count)->toBe(3);
});

it('returns zero when there are no holidays for the requested year', function () {
    $this->mock(BankHolidayService::class)
        ->shouldReceive('getNormalizedBankHolidays')
        ->andReturn([
            ['date' => '2023-01-02', 'title' => 'Bank Holiday: New Year\'s Day'],
        ]);

    $count = app(ImportBankHolidaysAction::class)->handle(2024);

    expect($count)->toBe(0);
});

it('updates an existing CalendarNote rather than creating a duplicate', function () {
    $this->mock(BankHolidayService::class)
        ->shouldReceive('getNormalizedBankHolidays')
        ->andReturn([
            ['date' => '2024-01-01', 'title' => 'Bank Holiday: New Year\'s Day'],
        ]);

    $action = app(ImportBankHolidaysAction::class);
    $action->handle(2024);
    $action->handle(2024);

    expect(CalendarNote::where('date', '2024-01-01')->where('source', 'bank_holiday')->count())->toBe(1);
});

it('sets the source to bank_holiday on all created notes', function () {
    $this->mock(BankHolidayService::class)
        ->shouldReceive('getNormalizedBankHolidays')
        ->andReturn([
            ['date' => '2024-01-01', 'title' => 'Bank Holiday: New Year\'s Day'],
            ['date' => '2024-03-29', 'title' => 'Bank Holiday: Good Friday'],
        ]);

    app(ImportBankHolidaysAction::class)->handle(2024);

    expect(CalendarNote::where('source', 'bank_holiday')->count())->toBe(2);
});

it('returns zero and creates no notes when the service returns an empty array', function () {
    $this->mock(BankHolidayService::class)
        ->shouldReceive('getNormalizedBankHolidays')
        ->andReturn([]);

    $count = app(ImportBankHolidaysAction::class)->handle(2024);

    expect($count)->toBe(0);
    expect(CalendarNote::count())->toBe(0);
});
