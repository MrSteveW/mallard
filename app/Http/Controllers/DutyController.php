<?php

namespace App\Http\Controllers;

use App\Actions\GenerateMonthlyDuties;
use App\Enums\ShiftType;
use App\Http\Resources\CalendarNoteResource;
use App\Http\Resources\DutyAssignResource;
use App\Http\Resources\DutyCalendarResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\CalendarNote;
use App\Models\Duty;
use App\Models\DutyGenerationRun;
use App\Models\Task;
use App\Models\User;
use App\Rules\QuarterHourTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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
                'grade' => $user->employee?->grade->name ?? '',
            ]),
            'generatedMonths' => $generatedMonths,
            'calendarNotes' => CalendarNoteResource::collection(CalendarNote::orderBy('date')->get()),
        ]);
    }

    public function apiCalendar(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $request->validate([
            'start' => ['required', 'string'],
            'end' => ['required', 'string'],
            'include_cancelled' => ['sometimes', 'boolean'],
        ]);

        $start = Carbon::parse($request->start)->toDateString();
        $end = Carbon::parse($request->end)->toDateString();

        $query = Duty::with(['user' => fn ($q) => $q->withTrashed(), 'user.employee.grade'])
            ->whereBetween('date', [$start, $end]);

        if (! $request->boolean('include_cancelled')) {
            $query->whereNull('cancelled_at');
        }

        return DutyCalendarResource::collection($query->get());
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'task_id' => ['nullable', 'exists:tasks,id'],
            'date' => ['required', 'date_format:Y-m-d'],
            'shift_type' => ['required', Rule::enum(ShiftType::class)],
            'start_time' => ['required', new QuarterHourTime],
            'end_time' => ['required', new QuarterHourTime],
            'notes' => ['nullable', 'string'],
        ]);

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = Carbon::createFromFormat('H:i', $validated['end_time']);
        if ($end->lessThanOrEqualTo($start)) {
            $end->addDay();
        }
        $validated['duration'] = $start->diffInMinutes($end);

        Duty::create($validated);

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
            'user_id' => ['required', 'exists:users,id'],
            'task_id' => ['nullable', 'exists:tasks,id'],
            'date' => ['required', 'date_format:Y-m-d'],
            'shift_type' => ['required', Rule::enum(ShiftType::class)],
            'start_time' => ['required', new QuarterHourTime],
            'end_time' => ['required', new QuarterHourTime],
            'duration' => ['nullable', 'integer'],
            'notes' => ['nullable', 'string'],
        ]);

        $duty->update($validated);

        return redirect()->back();
    }

    public function destroy(Duty $duty)
    {
        $duty->delete();

        return redirect('/duties');
    }

    public function showTasks(string $date): \Inertia\Response
    {
        $duties = Duty::with(['user', 'user.employee.grade', 'task'])
            ->where('date', $date)
            ->get();

        return Inertia::render('Duties/ShowTasks', [
            'date' => $date,
            'duties' => DutyAssignResource::collection($duties),
            'users' => UserResource::collection(User::with('employee.grade')->get()),
            'tasks' => TaskResource::collection(Task::all()),
        ]);
    }

    public function updateTasks(string $date, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'duties' => ['required', 'array'],
            'duties.*.id' => ['required', 'integer', 'exists:duties,id'],
            'duties.*.task_id' => ['nullable', 'integer', 'exists:tasks,id'],
        ]);

        foreach ($validated['duties'] as $item) {
            Duty::where('id', $item['id'])
                ->where('date', $date)
                ->update(['task_id' => $item['task_id']]);
        }

        return redirect()->back();
    }
}
