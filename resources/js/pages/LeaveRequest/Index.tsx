import { Head, Link, router } from '@inertiajs/react';
import ConfirmModal from '@/components/ConfirmModal';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { LeaveRequest } from '@/types.ts';

const formatDate = (iso: string) =>
    new Date(iso + 'T00:00:00').toLocaleDateString('en-GB', {
        day: 'numeric',
        month: 'long',
    });

interface IndexProps {
    leaveRequests: LeaveRequest[];
}

export default function Create({ leaveRequests }: IndexProps) {
    return (
        <AppLayout>
            <Head title="Shift patterns" />
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

                <div className="mx-50">
                    {leaveRequests.map((LR) => (
                        <div className="my-2 grid grid-cols-5 justify-items-center gap-2 rounded-lg border border-mallard-green p-2">
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
                            <div>{LR.approved}</div>
                            <div>
                                {LR.approved === 'Pending' && (
                                    <ConfirmModal
                                        title="Delete leave request"
                                        description={`Delete leave request starting on ${LR.dates[0]}?`}
                                        confirmLabel="Delete"
                                        onConfirm={() =>
                                            router.delete(
                                                `/leaverequests/${LR.id}`,
                                            )
                                        }
                                        trigger={
                                            <Button variant="destructive">
                                                Delete
                                            </Button>
                                        }
                                    />
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
