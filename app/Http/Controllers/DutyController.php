<?php

namespace App\Http\Controllers;

use App\Actions\GenerateMonthlyDuties;
use App\Http\Resources\CalendarNoteResource;
use App\Http\Resources\TaskResource;
use App\Models\CalendarNote;
use App\Models\Duty;
use App\Models\DutyGenerationRun;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DutyController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'month' => ['required', 'date_format:Y-m'],
        ]);

        $month = Carbon::createFromFormat('Y-m-d', $request->month.'-01')->startOfMonth();
        (new GenerateMonthlyDuties)->handle($month, 'manual', Auth::id());

        return redirect()->back();
    }

    public function index()
    {

        $users = User::with('employee.grade')->get();
        $generatedMonths = DutyGenerationRun::pluck('year_month');

        return Inertia::render('Duties/Index', [
            'users' => $users->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'grade' => $user->employee->grade->name ?? '',
            ]),
            'generatedMonths' => $generatedMonths,
            'calendarNotes' => CalendarNoteResource::collection(CalendarNote::orderBy('date')->get()),
        ]);
    }

    public function apiIndex(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'start' => ['required', 'string'],
            'end' => ['required', 'string'],
            'include_cancelled' => ['sometimes', 'boolean'],
        ]);

        $start = Carbon::parse($request->start)->toDateString();
        $end = Carbon::parse($request->end)->toDateString();

        $query = Duty::with('user')
            ->whereBetween('date', [$start, $end]);

        if (! $request->boolean('include_cancelled')) {
            $query->whereNull('cancelled_at');
        }

        $duties = $query->get()
            ->map(fn (Duty $duty) => [
                'id' => $duty->id,
                'title' => $duty->user->name,
                'start' => $duty->date.'T'.$duty->start_time.':00',
                'end' => $duty->end_time < $duty->start_time
                    ? $duty->date.'T23:59:00'
                    : $duty->date.'T'.$duty->end_time.':00',
                'extendedProps' => [
                    'user_id' => $duty->user_id,
                    'shift_type' => $duty->shift_type,
                    'start_time' => $duty->start_time,
                    'end_time' => $duty->end_time,
                    'notes' => $duty->notes,
                    'grade' => $duty->user->employee?->grade?->name ?? '',
                    'cancelled_at' => $duty->cancelled_at,
                    'cancel_reason' => $duty->cancel_reason,
                    'sort_order' => $duty->cancelled_at ? 1 : 0,
                ],
            ]);

        return response()->json($duties);
    }

    public function create()
    {
        $users = User::with('employee.grade')->get();

        return Inertia::render('Duties/Create', [
            'users' => $users->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'grade' => $user->employee->grade->name ?? '',
            ]),
            'tasks' => TaskResource::collection(Task::all()),
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
        $end = Carbon::createFromFormat('H:i', $validated['end_time']);
        if ($end->lessThanOrEqualTo($start)) {
            $end->addDay();
        }
        $validated['duration'] = $start->diffInMinutes($end);

        Duty::create($validated);

        return redirect()->back();

        return redirect('/duties');
    }

    public function cancel(Duty $duty, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'cancel_reason' => ['required', 'string'],
        ]);

        $duty->update([
            'cancelled_at' => now(),
            'cancelled_by' => Auth::id(),
            'cancel_reason' => $validated['cancel_reason'],
        ]);

        return redirect()->back();
    }

    public function update(Duty $duty, Request $request)
    {
        $request->merge([
            'start_time' => $request->start_time ? substr($request->start_time, 0, 5) : null,
            'end_time' => $request->end_time ? substr($request->end_time, 0, 5) : null,
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
}
