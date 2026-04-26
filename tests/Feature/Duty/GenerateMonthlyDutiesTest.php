<?php

use App\Actions\GenerateMonthlyDuties;
use App\Models\Duty;
use App\Models\DutyGenerationRun;
use App\Models\ShiftPattern;
use App\Models\ShiftRepeat;
use App\Models\User;
use Carbon\Carbon;
use Tests\Traits\MocksUserObserver;

uses(MocksUserObserver::class);

// Anchor: 2024-01-01, 10-day cycle
// Jan 1 = pattern day 1, Jan 2 = pattern day 2, Jan 11 = pattern day 1 (wraps)
beforeEach(function () {
    ShiftRepeat::create([
        'total_days' => 10,
        'shift_pattern_start_date' => '2024-01-01',
    ]);
});

it('generates a duty for a non-off shift in the month', function () {
    $user = User::factory()->user()->create();
    ShiftPattern::create([
        'user_id' => $user->id,
        'day' => 1,
        'shift_type' => 'Day',
        'start_time' => '09:00',
        'end_time' => '17:00',
    ]);

    (new GenerateMonthlyDuties)->handle(Carbon::parse('2024-01-01'));

    $this->assertDatabaseHas('duties', [
        'user_id' => $user->id,
        'date' => '2024-01-01',
        'shift_type' => 'Day',
    ]);
});

it('does not generate a duty for an Off shift', function () {
    $user = User::factory()->user()->create();
    ShiftPattern::create([
        'user_id' => $user->id,
        'day' => 1,
        'shift_type' => 'Off',
        'start_time' => null,
        'end_time' => null,
    ]);

    (new GenerateMonthlyDuties)->handle(Carbon::parse('2024-01-01'));

    $this->assertDatabaseMissing('duties', [
        'user_id' => $user->id,
        'date' => '2024-01-01',
    ]);
});

it('calculates duration correctly for a normal shift', function () {
    $user = User::factory()->user()->create();
    ShiftPattern::create([
        'user_id' => $user->id,
        'day' => 1,
        'shift_type' => 'Day',
        'start_time' => '09:00',
        'end_time' => '17:00',
    ]);

    (new GenerateMonthlyDuties)->handle(Carbon::parse('2024-01-01'));

    $duty = Duty::where('user_id', $user->id)->where('date', '2024-01-01')->first();
    expect($duty->duration)->toBe(480);
});

it('calculates duration correctly for an overnight shift', function () {
    $user = User::factory()->user()->create();
    ShiftPattern::create([
        'user_id' => $user->id,
        'day' => 1,
        'shift_type' => 'Night',
        'start_time' => '22:00',
        'end_time' => '06:00',
    ]);

    (new GenerateMonthlyDuties)->handle(Carbon::parse('2024-01-01'));

    $duty = Duty::where('user_id', $user->id)->where('date', '2024-01-01')->first();
    expect($duty->duration)->toBe(480);
});

it('stores the generated_from_pattern_day on the duty', function () {
    $user = User::factory()->user()->create();
    ShiftPattern::create([
        'user_id' => $user->id,
        'day' => 1,
        'shift_type' => 'Day',
        'start_time' => '09:00',
        'end_time' => '17:00',
    ]);

    (new GenerateMonthlyDuties)->handle(Carbon::parse('2024-01-01'));

    $this->assertDatabaseHas('duties', [
        'user_id' => $user->id,
        'date' => '2024-01-01',
        'generated_from_pattern_day' => 1,
    ]);
});

it('assigns the correct pattern day when the cycle wraps around', function () {
    $user = User::factory()->user()->create();
    // Jan 11 is 10 days after anchor, so (10 % 10) + 1 = 1
    ShiftPattern::create([
        'user_id' => $user->id,
        'day' => 1,
        'shift_type' => 'Day',
        'start_time' => '09:00',
        'end_time' => '17:00',
    ]);

    (new GenerateMonthlyDuties)->handle(Carbon::parse('2024-01-01'));

    $this->assertDatabaseHas('duties', [
        'user_id' => $user->id,
        'date' => '2024-01-11',
        'generated_from_pattern_day' => 1,
    ]);
});

it('does not create duplicate duties when run twice for the same month', function () {
    $user = User::factory()->user()->create();
    ShiftPattern::create([
        'user_id' => $user->id,
        'day' => 1,
        'shift_type' => 'Day',
        'start_time' => '09:00',
        'end_time' => '17:00',
    ]);

    $action = new GenerateMonthlyDuties;
    $action->handle(Carbon::parse('2024-01-01'));
    $action->handle(Carbon::parse('2024-01-01'));

    expect(Duty::where('user_id', $user->id)->where('date', '2024-01-01')->count())->toBe(1);
});

it('creates a DutyGenerationRun record for the month', function () {
    (new GenerateMonthlyDuties)->handle(Carbon::parse('2024-01-01'));

    $this->assertDatabaseHas('duty_generation_runs', [
        'year_month' => '2024-01',
    ]);
});

it('does not duplicate the DutyGenerationRun when run twice', function () {
    $action = new GenerateMonthlyDuties;
    $action->handle(Carbon::parse('2024-01-01'));
    $action->handle(Carbon::parse('2024-01-01'));

    expect(DutyGenerationRun::where('year_month', '2024-01')->count())->toBe(1);
});

it('defaults triggered_by to schedule', function () {
    (new GenerateMonthlyDuties)->handle(Carbon::parse('2024-01-01'));

    $this->assertDatabaseHas('duty_generation_runs', [
        'year_month' => '2024-01',
        'triggered_by' => 'schedule',
    ]);
});

it('records triggered_by and user_id when provided', function () {
    $admin = User::factory()->admin()->create();

    (new GenerateMonthlyDuties)->handle(Carbon::parse('2024-01-01'), 'manual', $admin->id);

    $this->assertDatabaseHas('duty_generation_runs', [
        'year_month' => '2024-01',
        'triggered_by' => 'manual',
        'user_id' => $admin->id,
    ]);
});

it('generates duties for multiple users on the same pattern day', function () {
    $userA = User::factory()->user()->create();
    $userB = User::factory()->user()->create();

    ShiftPattern::create(['user_id' => $userA->id, 'day' => 1, 'shift_type' => 'Day', 'start_time' => '09:00', 'end_time' => '17:00']);
    ShiftPattern::create(['user_id' => $userB->id, 'day' => 1, 'shift_type' => 'Night', 'start_time' => '21:00', 'end_time' => '09:00']);

    (new GenerateMonthlyDuties)->handle(Carbon::parse('2024-01-01'));

    $this->assertDatabaseHas('duties', ['user_id' => $userA->id, 'date' => '2024-01-01']);
    $this->assertDatabaseHas('duties', ['user_id' => $userB->id, 'date' => '2024-01-01']);
});
