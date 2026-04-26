<?php

use App\Services\BankHolidayService;
use Illuminate\Support\Facades\Http;

it('returns normalized bank holidays from the API', function () {
    Http::fake([
        'www.gov.uk/bank-holidays.json' => Http::response([
            'england-and-wales' => [
                'events' => [
                    ['date' => '2024-01-01', 'title' => 'New Year\'s Day'],
                    ['date' => '2024-03-29', 'title' => 'Good Friday'],
                ],
            ],
        ], 200),
    ]);

    $result = (new BankHolidayService)->getNormalizedBankHolidays();

    expect($result)->toHaveCount(2);
    expect($result[0]['date'])->toBe('2024-01-01');
    expect($result[1]['date'])->toBe('2024-03-29');
});

it('strips "bank holiday" from the title and prepends the prefix', function () {
    Http::fake([
        'www.gov.uk/bank-holidays.json' => Http::response([
            'england-and-wales' => [
                'events' => [
                    ['date' => '2024-08-26', 'title' => 'Summer Bank Holiday'],
                ],
            ],
        ], 200),
    ]);

    $result = (new BankHolidayService)->getNormalizedBankHolidays();

    expect($result[0]['title'])->toBe('Bank Holiday: Summer');
});

it('prepends the Bank Holiday prefix to a normal title', function () {
    Http::fake([
        'www.gov.uk/bank-holidays.json' => Http::response([
            'england-and-wales' => [
                'events' => [
                    ['date' => '2024-01-01', 'title' => 'New Year\'s Day'],
                ],
            ],
        ], 200),
    ]);

    $result = (new BankHolidayService)->getNormalizedBankHolidays();

    expect($result[0]['title'])->toBe('Bank Holiday: New Year\'s Day');
});

it('returns an empty array when the API request fails', function () {
    Http::fake([
        'www.gov.uk/bank-holidays.json' => Http::response([], 500),
    ]);

    $result = (new BankHolidayService)->getNormalizedBankHolidays();

    expect($result)->toBeEmpty();
});

it('returns an empty array when the england-and-wales key is missing', function () {
    Http::fake([
        'www.gov.uk/bank-holidays.json' => Http::response([
            'scotland' => ['events' => []],
        ], 200),
    ]);

    $result = (new BankHolidayService)->getNormalizedBankHolidays();

    expect($result)->toBeEmpty();
});

it('filters out events missing a date or title', function () {
    Http::fake([
        'www.gov.uk/bank-holidays.json' => Http::response([
            'england-and-wales' => [
                'events' => [
                    ['date' => '2024-01-01', 'title' => 'New Year\'s Day'],
                    ['date' => '2024-03-29'],
                    ['title' => 'Good Friday'],
                    ['date' => null, 'title' => null],
                ],
            ],
        ], 200),
    ]);

    $result = (new BankHolidayService)->getNormalizedBankHolidays();

    expect($result)->toHaveCount(1);
    expect($result[0]['date'])->toBe('2024-01-01');
});

it('returns an empty array when the API throws an exception', function () {
    Http::fake(function () {
        throw new \RuntimeException('Connection refused');
    });

    $result = (new BankHolidayService)->getNormalizedBankHolidays();

    expect($result)->toBeEmpty();
});
