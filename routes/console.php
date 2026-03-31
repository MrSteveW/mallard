<?php
use App\Actions\GenerateMonthlyDuties;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schedule;

Schedule:: call(function () {
    (new GenerateMonthlyDuties())
    ->handle(Carbon::now()->addMonths(6)->startOfMonth());
})->monthlyOn(1, '00:00');