<?php

use App\Actions\GenerateMonthlyDuties;
use App\Actions\ImportBankHolidaysAction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    (new GenerateMonthlyDuties)
        ->handle(Carbon::now()->addMonths(6)->startOfMonth());
})->monthlyOn(1, '00:00');

// One-time backfill command (current year + next year)
Artisan::command('bank-holidays:backfill', function (ImportBankHolidaysAction $action) {
    $currentYear = now()->year;
    $nextYear = now()->addYear()->year;

    $countCurrent = $action->handle($currentYear);
    $countNext = $action->handle($nextYear);

    $this->info("Imported/updated {$countCurrent} holidays for {$currentYear}");
    $this->info("Imported/updated {$countNext} holidays for {$nextYear}");
})->purpose('Backfill bank holidays for current and next year');

Schedule::call(function (ImportBankHolidaysAction $action) {
    $targetYear = now()->addYear()->year;
    $action->handle($targetYear);
})->yearlyOn(1, 1, '00:00');

Artisan::command('app:cleanse', function () {
    $this->info('Running Pint...');
    passthru('vendor/bin/pint --dirty --format agent');

    $this->info('Running tests...');
    $this->call('test', ['--compact' => true]);

    $this->info('Running PHPStan...');
    passthru('vendor/bin/phpstan analyse');
})->purpose('Run Pint, tests, and PHPStan before pushing');

Artisan::command('app:migrate:fresh', function () {
    $this->call('migrate:fresh', ['--seed' => true]);

    $this->call('bank-holidays:backfill');

    $this->info('Ran migrations, seeded data and backfilled bank holidays.');
})->purpose('Run migrate:fresh --seed and bank-holidays:backfill in sequence');
