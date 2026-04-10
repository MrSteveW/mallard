<?php

namespace App\Http\Controllers;

use App\Actions\GenerateMonthlyDuties;
use App\Http\Resources\CalendarNoteResource;
use App\Models\Duty;
use App\Models\User;
use App\Models\Task;
use App\Models\CalendarNote;
use App\Http\Resources\TaskResource;
use App\Models\DutyGenerationRun;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\BankHolidayService;

class DutyController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
             'month' => ['required', 'date_format:Y-m']
        ]);

        $month = Carbon::createFromFormat('Y-m-d', $request->month . '-01')->startOfMonth();
        (new GenerateMonthlyDuties())->handle($month, 'manual', Auth::id());
        return redirect()->back();
    }

    public function index()
    {
        
        $users = User::with('employee.grade')->get();
        $generatedMonths = DutyGenerationRun::pluck('year_month');

        return Inertia::render('Duties/Index', [
            'users' => $users->map(fn($user) => [
                'id'    => $user->id,
                'name'  => $user->name,
                'grade' => $user->employee->grade->name ?? '',
            ]),
            'generatedMonths' => $generatedMonths,
            'calendarNotes' => CalendarNoteResource::collection(CalendarNote::orderBy('date')->get()),
        ]);
    }

    public function apiIndex(Request $request) {
            $request->validate([
                'start' => ['required', 'string'],
                'end'   => ['required', 'string'],
            ]);
            $start = Carbon::parse($request->start)->toDateString();
            $end   = Carbon::parse($request->end)->toDateString();

            $duties = Duty::with('user')
                ->whereBetween('date', [$start, $end])
                ->get()
                ->map(fn (Duty $duty) => [
                    'id'    => $duty->id,
                    'title' => $duty->user->name,
                    'start' => $duty->date . 'T' . $duty->start_time . ':00',
                    'end'   => $duty->end_time < $duty->start_time
                        ? $duty->date . 'T23:59:00'
                        : $duty->date . 'T' . $duty->end_time . ':00',
                    'extendedProps' => [
                        'user_id'    => $duty->user_id,
                        'shift_type' => $duty->shift_type,
                        'start_time' => $duty->start_time,
                        'end_time'   => $duty->end_time,
                        'notes'      => $duty->notes,
                        'grade'      => $duty->user->employee?->grade?->name ?? '',
                    ],
                ]);

            return response()->json($duties);
    }


    public function create()
    {
        $users = User::with('employee.grade')->get();
        return Inertia::render('Duties/Create', [
            'users' => $users->map(fn($user) => [
                'id'    => $user->id,
                'name'  => $user->name,
                'grade' => $user->employee->grade->name ?? '',
            ]),
            'tasks' => TaskResource::collection(Task::all())
        ]);
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'task_id' => ['nullable', 'integer'], 
            'date' => ['required', 'date_format:Y-m-d'],
            'shift_type' => ['required', 'string'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string'],
        ]);

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end   = Carbon::createFromFormat('H:i', $validated['end_time']);
         if ($end->lessThanOrEqualTo($start)) {
            $end->addDay();
            }
        $validated['duration'] = $start->diffInMinutes($end);

        Duty::create($validated);
        return redirect()->back();
    return redirect('/duties');
}

    public function update(Duty $duty, Request $request)
    {
        $request->merge([
            'start_time' => $request->start_time ? substr($request->start_time, 0, 5) : null,
            'end_time'   => $request->end_time   ? substr($request->end_time, 0, 5)   : null,
        ]);

        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
            'task_id' => ['nullable', 'integer'], 
            'date' => ['required', 'date_format:Y-m-d'],
            'shift_type' => ['required', 'string'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'duration' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string'],
        ]);

        $duty->update($validated);
        return redirect()->back();
    }




    public function destroy(Duty $duty, Request $request)
    {
        $request->validate([
            'absence' => ['required', 'string'],
        ]);

        $duty->absence = $request->absence;
        $duty->save();
        
        $duty->delete();
        return redirect('/duties');
    }
}