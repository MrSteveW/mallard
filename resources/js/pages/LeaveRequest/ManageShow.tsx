import { Head, router, usePage } from '@inertiajs/react';
import ConfirmModal from '@/components/ConfirmModal';
import { PrimaryLink } from '@/components/ui/primary-link';
import AppLayout from '@/layouts/app-layout';
import { formatDatesRange } from '@/lib/utils';
import type { SharedData } from '@/types/index';
import type { ManagerLeaveRequest, StaffingData } from '@/types.ts';

const formatLongDate = (iso: string) =>
    new Date(iso + 'T00:00:00').toLocaleDateString('en-GB', {
        weekday: 'short',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

interface ShowProps {
    leaveRequest: ManagerLeaveRequest;
    staffingData: StaffingData[];
}

export default function ManageShow({ leaveRequest, staffingData }: ShowProps) {
    const { auth } = usePage<SharedData>().props;
    return (
        <AppLayout>
            <Head title="Manage a Leave Request" />
            <div className="p-3"><PrimaryLink href="/manageleaverequests">
                                    &lt; Back
                                </PrimaryLink></div>
            
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
                <div>
                    <div>Staff: {leaveRequest.user_name}</div>
                    <div>Grade: {leaveRequest.user_grade}</div>
                    <div>Dates: {formatDatesRange(leaveRequest.dates)}</div>
                    <div>Optional start/end: {leaveRequest.start_time}-{leaveRequest.end_time}</div>
                    <div>Leave Reason: {leaveRequest.leave_reason}</div>
                    <div>Status: {leaveRequest.status}</div>
                    <div>Optional: approved/declined by {leaveRequest.manager_name}</div>
                </div>
                <div className="flex flex-row gap-2 overflow-auto border bg-gray-200">
                    {staffingData.map((day) => (
                        <div className="bg-amber-200">
                             
                                <div>
                                    <div>{formatLongDate(day.date)}</div>
                                    <div>{day.staffCount.map((grade) => (
                                        <div className=" flex">
                                            <div>{grade.gradeName}</div>
                                            <div>{grade.gradeCount}</div>
                                        </div>
                                        ))}
                                    </div>
                                </div>
                                
                        </div>
                        ))}
                        </div>
            </div>
        </AppLayout>
    );
}
