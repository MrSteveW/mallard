import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import type { ManagerLeaveRequest } from '@/types.ts';

const formatDate = (iso: string) =>
    new Date(iso + 'T00:00:00').toLocaleDateString('en-GB', {
        day: 'numeric',
        month: 'long',
    });

interface ManageIndexProps {
    approvedRequests: ManagerLeaveRequest[];
}

export default function ManageIndex({ approvedRequests }: ManageIndexProps) {
    return (
        <AppLayout>
            <Head title="Manage Leave Requests" />
            <div className="relative my-3 h-[calc(100vh-100px)] w-full overflow-auto rounded-lg border bg-slate-50">
                <div>{JSON.stringify(approvedRequests)}</div>
                <div className="mx-50">
                    {approvedRequests.map((LR) => (
                        <div className="my-2 grid grid-cols-6 justify-items-center gap-2 rounded-lg border border-mallard-green p-2">
                            <div>{LR.user_name}</div>
                            <div>
                                {LR.dates.length > 1
                                    ? `${formatDate(LR.dates[0])} - ${formatDate(LR.dates[LR.dates.length - 1])}`
                                    : formatDate(LR.dates[0])}
                            </div>
                            <div>
                                {LR.start_time
                                    ? `${LR.start_time} - ${LR.end_time}`
                                    : ''}
                            </div>
                            <div>{LR.leave_reason}</div>
                            <div>{LR.status}</div>
                            <div>REVIEW</div>
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
