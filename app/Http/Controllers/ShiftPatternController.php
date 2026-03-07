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

class ShiftPatternController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shiftpatterns = ShiftPattern::with('user')->get();
        $groupedPatterns = $shiftpatterns->groupBy('user_id')->map(function ($shifts) {
        $firstShift = $shifts->first();
         return [
            'user_id' => $firstShift->user_id,
            'user_name' => $firstShift->user->name,
            'shift_pattern' => ShiftPatternResource::collection($shifts),
        ];
        })->values(); 

        $shiftRepeat = ShiftRepeat::first();
        $totalDays = $shiftRepeat?->total_days ?? 7; 
        $days = collect(range(1, $totalDays))->map(function ($number) {
        return [
            'number' => $number,
            'name' => Carbon::create()->startOfWeek()->addDays(($number - 1) % 7)->dayName
         ];
        });

        return Inertia::render('ShiftPatterns/Index', [
            'shiftpatterns' => $groupedPatterns,
            'days' => $days,
        ]);
    }

    public function create()
    {
        $users = User::select('id', 'name')->get();
        $shiftRepeat = ShiftRepeat::first();
        $totalDays = $shiftRepeat?->total_days ?? 7; 

          return Inertia::render('ShiftPatterns/Create', [
            'userOptions' => $users,
            'totalDays' => $totalDays
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


    public function edit(ShiftPattern $shiftPattern)
    {
        
    }


    public function update(Request $request, ShiftPattern $shiftPattern)
    {
        
    }


    public function destroy(ShiftPattern $shiftPattern)
    {
        
    }
}
