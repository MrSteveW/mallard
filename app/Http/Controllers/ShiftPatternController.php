<?php

namespace App\Http\Controllers;

use App\Enums\ShiftType;
use App\Models\ShiftPattern;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Resources\UserResource;
use App\Http\Resources\ShiftPatternResource;
use App\Models\User;
use App\Models\ShiftRepeat;
use Illuminate\Support\Carbon;
use App\Rules\ValidShiftTime;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class ShiftPatternController extends Controller
{
    public function index()
    {
        $users = User::with('shiftPatterns')->get();
        $groupedPatterns = $users->map(function ($user) {
        return [
            'user_id'       => $user->id,
            'user_name'     => $user->name,
            'shift_pattern' => ShiftPatternResource::collection($user->shiftPatterns),
        ];
    })->values();

        $shiftRepeat = ShiftRepeat::first();
        $totalDays = $shiftRepeat?->total_days ?? 7; 
        $dayNames = collect(range(1, $totalDays))->map(function ($number) {
        return [
            'number' => $number,
            'name' => Carbon::create()->startOfWeek()->addDays(($number - 1) % 7)->dayName
         ];
        });

        return Inertia::render('ShiftPatterns/Index', [
            'shiftpatterns' => $groupedPatterns,
            'dayNames' => $dayNames,
        ]);
    }


    public function store(Request $request)
{   
    $shiftRepeat = ShiftRepeat::first();

    $validated = $request->validate([
        'shiftArray'                => ['required', 'array', 'min:1'],
        'shiftArray.*.user_id'      => ['required', 'integer', 'exists:users,id'],
        'shiftArray.*.day'          => ['required', 'integer', 'min:1', 'max:' . $shiftRepeat->total_days],
        'shiftArray.*.shift_type'   => ['required', new Enum(ShiftType::class)],
        'shiftArray.*.start_time' => ['nullable', 'required_unless:shiftArray.*.shift_type,Off', 'date_format:H:i'],
        'shiftArray.*.end_time'   => ['nullable', 'required_unless:shiftArray.*.shift_type,Off', 'date_format:H:i']
    ]);

    foreach ($validated['shiftArray'] as $index => $shift) {
    if (
        $shift['shift_type'] !== 'Night' &&
        !empty($shift['start_time']) &&
        !empty($shift['end_time']) &&
        $shift['end_time'] <= $shift['start_time']
    ) {
        throw ValidationException::withMessages([
            "shiftArray.{$index}.end_time" => 'End time must be after start time.',
        ]);
    }

    ShiftPattern::create($shift);
}
}


    public function show(ShiftPattern $shiftPattern)
    {
        
    }


    public function edit(User $user)
    {
    return Inertia::render('ShiftPatterns/Edit', [
        'user' => $user->only('id', 'name'),
        'initialPattern' => ShiftPatternResource::collection($user->shiftPatterns),
    ]);
    }


    public function update(Request $request, User $user)
{
       $validated = $request->validate([
        'shiftArray'             => ['required', 'array'],
        'shiftArray.*.day'       => ['required', 'integer'],
        'shiftArray.*.shift_type'=> ['required', 'string'],
        'shiftArray.*.start_time'=> ['nullable', 'string'],
        'shiftArray.*.end_time'  => ['nullable', 'string'],
    ]);

    DB::transaction(function () use ($validated, $user) {
        foreach ($validated['shiftArray'] as $day) {
            ShiftPattern::where('user_id', $user->id)
                ->where('day', $day['day'])
                ->update([
                    'shift_type' => $day['shift_type'],
                    'start_time' => $day['start_time'] ?? null,
                    'end_time'   => $day['end_time'] ?? null,
                ]);
        }
    });

    return redirect('/shiftpatterns')->with('message', 'Shift pattern saved successfully.');
}


    public function destroy(ShiftPattern $shiftPattern)
    {
        
    }
}
