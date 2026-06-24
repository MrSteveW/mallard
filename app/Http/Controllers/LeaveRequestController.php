<?php

namespace App\Http\Controllers;

use App\Enums\LeaveOptions;
use App\Http\Resources\LeaveRequestManagerResource;
use App\Http\Resources\LeaveRequestUserResource;
use App\Models\Duty;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class LeaveRequestController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', LeaveRequest::class);
        $user = Auth::user();
        $leaveRequests = LeaveRequestUserResource::collection($user
            ->leaveRequests()
            ->whereTodayOrAfter()
            ->when(
                DB::connection()->getDriverName() === 'pgsql',
                fn ($q) => $q->orderByRaw('(dates->>0)::date'),
                fn ($q) => $q->orderByRaw("json_extract(dates, '$[0]')")
            )
            ->get());

        return Inertia::render('LeaveRequest/Index', [
            'leaveRequests' => $leaveRequests,
        ]);
    }

    public function create()
    {
        Gate::authorize('viewAny', LeaveRequest::class);
        $user = Auth::user();
        $dutyDates = $user->duties()->pluck('date')->toArray();

        return Inertia::render('LeaveRequest/Create', [
            'dutyDates' => $dutyDates,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', LeaveRequest::class);
        $user = Auth::user();

        $validated = $request->validate([
            'leave_reason' => ['required', Rule::enum(LeaveOptions::class)],
            'mode' => ['required',  Rule::in(['partial_day', 'multiple_days', 'range'])],
            'start_time' => [Rule::requiredIf($request->mode === 'partial_day')],
            'end_time' => [Rule::requiredIf($request->mode === 'partial_day')],
            'dates' => ['required', 'array', 'min:1'],
            'dates.*' => ['required', 'date_format:Y-m-d', 'after:today'],
            'notes' => ['nullable', 'string'],
        ]);

        LeaveRequest::create([
            'user_id' => $user->id,
            'leave_reason' => $validated['leave_reason'],
            'dates' => $validated['dates'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect('/leaverequests');
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        //
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        Gate::authorize('delete', $leaveRequest);
        $leaveRequest->delete();

        return redirect('/leaverequests');
    }

    public function manageIndex()
    {
        Gate::authorize('manage', LeaveRequest::class);
        $leaveRequests = LeaveRequestManagerResource::collection(LeaveRequest::when(
            DB::connection()->getDriverName() === 'pgsql',
            fn ($q) => $q->orderByRaw('(dates->>0)::date'),
            fn ($q) => $q->orderByRaw("json_extract(dates, '$[0]')")
        )
            ->get());

        return Inertia::render('LeaveRequest/ManageIndex', [
            'leaveRequests' => $leaveRequests,
        ]);
    }

    public function manageShow(LeaveRequest $leaveRequest)
    {
        $staffingData = [];
        $dutyData = Duty::with(['user.employee.grade'])->whereIn('date', $leaveRequest->dates)->get();

        foreach ($leaveRequest->dates as $date) {
            $filteredDateDuties = $dutyData->filter(function (Duty $duty) use ($date) {
                return $duty->date == $date;
            });
            $currentArray = [];
            $currentGradeCount = [];
            $staffCount = $filteredDateDuties
                ->sortBy(fn (Duty $duty) => $duty->user->employee->grade->id)
                ->countBy(fn (Duty $duty) => $duty->user->employee->grade->name);
            $staffCount = $staffCount->toArray();
            foreach ($staffCount as $key => $value) {

                $currentGrade['gradeName'] = $key;
                $currentGrade['gradeCount'] = $value;
                array_push($currentGradeCount, $currentGrade);
            }
            $currentArray['date'] = $date;
            $currentArray['staffCount'] = $currentGradeCount;
            array_push($staffingData, $currentArray);
        }

        return Inertia::render('LeaveRequest/ManageShow', [
            'staffingData' => $staffingData,
            'leaveRequest' => new LeaveRequestManagerResource($leaveRequest),
        ]);
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        Gate::authorize('manage', $leaveRequest);
        $user = Auth::user();
        $leaveRequest->updateOrFail(['approved_by' => $user->id]);

        return redirect('/manageleaverequests');
    }

    public function decline(LeaveRequest $leaveRequest)
    {
        Gate::authorize('manage', $leaveRequest);
        $user = Auth::user();
        $leaveRequest->updateOrFail(['declined_by' => $user->id]);

        return redirect('/manageleaverequests');
    }
}
