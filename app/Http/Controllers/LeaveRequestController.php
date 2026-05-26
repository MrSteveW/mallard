<?php

namespace App\Http\Controllers;

use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class LeaveRequestController extends Controller
{
    public function manage()
    {
        Gate::authorize('manage', LeaveRequest::class);

        return Inertia::render('LeaveRequest/Manage', [
            // 'user' => $user->only('id', 'name'),
            // 'initialPattern' => ShiftPatternResource::collection($user->shiftPatterns),
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        $approvedLeaveRequests = LeaveRequestResource::collection($user
        ->leaveRequests()
        ->whereNotNull('approved_by')
        ->whereTodayOrAfter('date')
        ->get());

        $pendingLeaveRequests = LeaveRequestResource::collection($user
        ->leaveRequests()
        ->whereNull('approved_by')
        ->whereNull('declined_by')
        ->whereTodayOrAfter('date')
        ->get());

         return Inertia::render('LeaveRequest/Index', [
            'approvedLeaveRequests' => $approvedLeaveRequests,
            'pendingLeaveRequests' => $pendingLeaveRequests,
        ]);
    }

    public function create()
    {
         return Inertia::render('LeaveRequest/Create', [
            // 'user' => $user->only('id', 'name'),
            // 'initialPattern' => ShiftPatternResource::collection($user->shiftPatterns),
        ]);
    }

    public function store(Request $request)
    {
        //
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
