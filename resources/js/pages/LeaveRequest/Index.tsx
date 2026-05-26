import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import type { LeaveRequest } from '@/types.ts';

interface IndexProps {
    approvedLeaveRequests: LeaveRequest[];
    pendingLeaveRequests: LeaveRequest[];
}

export default function Create({
    approvedLeaveRequests,
    pendingLeaveRequests,
}: IndexProps) {
    return (
        <AppLayout>
            <Head title="Shift patterns" />
            <div className="relative my-3 h-[calc(100vh-100px)] w-full overflow-auto rounded-lg border bg-slate-50">
                <div>
                    <Link
                        href={`/leaverequests/create`}
                        className="mr-2 bg-mallard-green p-2 text-white hover:text-mallard-orange"
                    >
                        Make Leave Request
                    </Link>
                </div>
                <div className="grid">
                    {/* --- STICKY HEADER --- */}
                    <div className="sticky top-0 z-20 flex items-center justify-center border-r border-b bg-white"></div>
                    <div className="sticky top-0 z-20 flex items-center justify-center border-r border-b bg-white"></div>
                    <div>Approved</div>
                    <div>
                        {approvedLeaveRequests.map((request) => (
                            <div className="flex gap-2" key={request.id}>
                                <div>{request.date}</div>
                                <div>{request.start_time}</div>
                                <div>{request.end_time}</div>
                                <div>{request.duration}</div>
                                <div>{request.leave_reason}</div>
                                <div>{request.notes}</div>
                            </div>
                        ))}
                    </div>

                    <div>Pending</div>
                    <div>
                        {pendingLeaveRequests.map((request) => (
                            <div className="flex gap-2">
                                <div>{request.date}</div>
                                <div>{request.start_time}</div>
                                <div>{request.end_time}</div>
                                <div>{request.duration}</div>
                                <div>{request.leave_reason}</div>
                                <div>{request.notes}</div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
