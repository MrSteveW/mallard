<?php

namespace App\Http\Controllers;

use App\Enums\LeaveOptions;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class LeaveRequestController extends Controller
{
    public function manage()
    {
        Gate::authorize('manage', LeaveRequest::class);

        return Inertia::render('LeaveRequest/Manage', [
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        $leaveRequests = LeaveRequestResource::collection($user
            ->leaveRequests()
            ->whereNull('declined_by')
            ->whereTodayOrAfter()
            ->get());

        return Inertia::render('LeaveRequest/Index', [
            'leaveRequests' => $leaveRequests,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $dutyDates = $user->duties()->pluck('date')->toArray();

        return Inertia::render('LeaveRequest/Create', [
            'dutyDates' => $dutyDates,
        ]);
    }

    public function store(Request $request)
    {
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
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'notes' => $validated['notes'],
        ]);

        return redirect('/leaverequests');
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        //
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        //
    }
}
