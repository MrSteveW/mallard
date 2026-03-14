<?php

namespace App\Http\Controllers;

use App\Models\Duty;
use App\Models\User;
use App\Models\Task;
use App\Http\Resources\DutyResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class DutyController extends Controller
{
    public function index(Request $request): Response|JsonResponse|InertiaResponse
    {
        if ($request->expectsJson()) {
            $request->validate([
                'start' => ['required', 'date'],
                'end'   => ['required', 'date'],
            ]);
            $start = Carbon::parse($request->start)->toDateString();
            $end   = Carbon::parse($request->end)->toDateString();

            $duties = Duty::with('user')
                ->whereBetween('date', [$start, $end])
                ->get()
                ->map(fn (Duty $duty) => [
                    'id'    => $duty->id,
                    'title' => $duty->user->name,
                    'start' => $duty->date . 'T' . $duty->start_time,
                    'end'   => $duty->end_time < $duty->start_time
                        ? $duty->date . 'T23:59:00'
                        : $duty->date . 'T' . $duty->end_time,
                    'extendedProps' => [
                        'user_id'    => $duty->user_id,
                        'shift_type' => $duty->shift_type,
                        'start_time' => Carbon::createFromFormat('H:i:s', $duty->start_time)->format('H:i'),
                        'end_time'   => Carbon::createFromFormat('H:i:s', $duty->end_time)->format('H:i'),
                        'notes'      => $duty->notes,
                        'grade'      => $duty->user->employee?->grade?->name ?? '',
                    ],
                ]);

            return response()->json($duties);
        }

        $users = User::with('employee.grade')->get();
        return Inertia::render('Duties/Index', [
            'users' => $users->map(fn($user) => [
                'id'    => $user->id,
                'name'  => $user->name,
                'grade' => $user->employee->grade->name ?? '',
            ]),
        ]);
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
        $validated['duration'] = $end->diffInMinutes($start);

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