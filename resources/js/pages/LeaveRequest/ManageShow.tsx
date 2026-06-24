import { Head, router, usePage } from '@inertiajs/react';
import {
    CalendarCheck, CalendarX, CalendarClock
} from 'lucide-react';
import ConfirmModal from '@/components/ConfirmModal';
import { Button } from '@/components/ui/button';
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
    });

const formatDateTime = (dt: Date) =>
    new Date(dt).toLocaleString('en-GB', {
        weekday: 'short',
        day: 'numeric',
        month: 'long',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
    }).replace(/,/g, '');

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
            <div className="flex h-full flex flex-col gap-4 items-center">

                <section className="w-3/4 flex gap-4 grid grid-cols-[3fr_1fr] ">
                    <div className="bg-mallard-yellow/50 grid grid-cols-2 border border-mallard-green rounded-xl p-4 text-lg gap-y-2">
                        <div>Staff: {leaveRequest.user_name}</div>
                        <div>Grade: {leaveRequest.user_grade}</div>
                        <div>Dates: {formatDatesRange(leaveRequest.dates)}</div>
                        <div>{leaveRequest.start_time ? `Hours requested: ${leaveRequest.start_time} - ${leaveRequest.end_time}` : ''}
                            </div>
                        <div>Number of days: {leaveRequest.dates.length}</div>
                        <div>Leave Reason: {leaveRequest.leave_reason}</div>
                        {leaveRequest.notes ? 
                        <div className="border border-black p-2 bg-slate-100 rounded mt-2 italic col-span-2 ">{leaveRequest.notes}</div> : '' }
                       
                        
                    </div>
                    <div className="bg-mallard-yellow/10 gap-8 flex flex-col border border-mallard-green rounded-xl p-4 text-lg">
                        <div className="flex grid grid-cols-2 items-center text-2xl">
                            <div>Status</div>
                            <div>
                                {(() => {
                                        if (leaveRequest.status === 'Pending') return <CalendarClock className="size-8" />;
                                        if (leaveRequest.status === 'Approved') return  <CalendarCheck className="size-8" />;
                                        return  <CalendarX className="size-8" />;
                                        })()}
                            </div>
                        </div>
                         <div>{leaveRequest.status} {leaveRequest.status === 'Pending' ? '' : `by ${leaveRequest.manager_name}` }</div>
                         <div>{leaveRequest.status === 'Pending' ? '' : `on ${formatDateTime(leaveRequest.updated_at)}`}</div>
                    </div>
                </section>

                <section className="w-3/4 border border-mallard-green rounded-xl bg-slate-100 p-2">
                    <div className="overflow-x-auto flex flex-row gap-2">
                        {staffingData[0].staffCount[0] ? 
                            staffingData.map((day) => (
                            <div className="shrink-0 m-1 p-2 border border-mallard-green rounded-lg mb-5">
                                
                                
                                        <div className="italic">{formatLongDate(day.date)}</div>
                                        <div>{day.staffCount.map((grade) => (
                                            <div className="flex gap-2 grid grid-cols-[2fr_1fr] text-sm px-1">
                                                <div className="text-mallard-green font-bold">{grade.gradeName}</div>
                                                <div>{grade.gradeCount}</div>
                                            </div>
                                            ))}
                                        </div>
                                    
                                    
                            </div>
                            )) : 'No Staffing data available'}
                        </div>
                    </section>

                    <section>
                        {leaveRequest.status === 'Pending' ?
                        <div className="flex gap-5">
                            <div>
                            <ConfirmModal
                                title="Approve leave request"
                                description={`Approve leave request starting on ${leaveRequest.dates[0]}?`}
                                confirmLabel="Approve"
                                onConfirm={() =>
                                    router.patch(
                                    `/manageleaverequests/${leaveRequest.id}/approve`,)
                                }
                                trigger={
                                    <Button
                                        variant="default"
                                        disabled={
                                            auth.user.role ===
                                            'Guest'
                                        }
                                    >
                                        Approve
                                    </Button>
                                }
                            />
                            </div>
                            <div>
                            <ConfirmModal
                                title="Decline leave request"
                                description={`Decline leave request starting on ${leaveRequest.dates[0]}?`}
                                confirmLabel="Decline"
                                onConfirm={() =>
                                    router.patch(
                                    `/manageleaverequests/${leaveRequest.id}/decline`,)
                                }
                                trigger={
                                    <Button
                                        variant="destructive"
                                        disabled={
                                            auth.user.role ===
                                            'Guest'
                                        }
                                    >
                                        Decline
                                    </Button>
                                }
                            />
                        
                            </div>
                        </div>
                        : '' }
                    </section>
            </div>
        </AppLayout>
    );
}
