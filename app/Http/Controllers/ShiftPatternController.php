<?php

namespace App\Http\Controllers;

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
            'users' => $users,
            'totalDays' => $totalDays
        ]);
    }


    public function store(Request $request)
    {   
        $validated = $request->validate([
            'user_id'=>['required', Rule::exists('users', 'id')],
            'day_number'=>['required', 'integer'],
            'start_time'=>['required', new ValidShiftTime],
            'end_time'=>['required', new ValidShiftTime],
            ]);

         $shiftpattern = ShiftPattern::create([
             'user_id' => $validated['user_id'],
             'day_number' => $validated['day_number'],
             'status' => 'On Duty',
             'start_time' => $validated['start_time'],
             'end_time' => $validated['end_time'],
              ]);
        return redirect('/shiftpatterns')->with('message', 'Shift pattern created successfully.');
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
