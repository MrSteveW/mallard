import { useMemo, useState } from 'react';
import { ChevronDown, ChevronUp, ChevronsUpDown } from 'lucide-react';
import { PrimaryLink } from '@/components/ui/primary-link';
import { formatDatesRange } from '@/lib/utils';
import type { ManagerLeaveRequest } from '@/types.ts';

type SortColumn = 'user_name' | 'dates' | 'leave_reason';
type SortDirection = 'asc' | 'desc';

interface TableProps {
    leaveRequests: ManagerLeaveRequest[];
}

function SortIcon({ column, activeColumn, direction }: { column: SortColumn; activeColumn: SortColumn | null; direction: SortDirection }) {
    if (column !== activeColumn) return <ChevronsUpDown className="size-3.5 text-muted-foreground" />;
    return direction === 'asc'
        ? <ChevronUp className="size-3.5" />
        : <ChevronDown className="size-3.5" />;
}

export default function LeaveRequestTable({ leaveRequests }: TableProps) {
    const [sortColumn, setSortColumn] = useState<SortColumn | null>(null);
    const [sortDirection, setSortDirection] = useState<SortDirection>('asc');

    function handleSort(column: SortColumn) {
        if (sortColumn === column) {
            setSortDirection((prev) => (prev === 'asc' ? 'desc' : 'asc'));
        } else {
            setSortColumn(column);
            setSortDirection('asc');
        }
    }

    const sorted = useMemo(() => {
        if (!sortColumn) return leaveRequests;

        return [...leaveRequests].sort((a, b) => {
            let comparison = 0;

            if (sortColumn === 'user_name') {
                comparison = a.user_name.localeCompare(b.user_name);
            } else if (sortColumn === 'dates') {
                comparison = a.dates[0].localeCompare(b.dates[0]);
            } else if (sortColumn === 'leave_reason') {
                comparison = (a.leave_reason ?? '').localeCompare(b.leave_reason ?? '');
            }

            return sortDirection === 'asc' ? comparison : -comparison;
        });
    }, [leaveRequests, sortColumn, sortDirection]);

    function SortableHeader({ column, label }: { column: SortColumn; label: string }) {
        return (
            <button
                type="button"
                onClick={() => handleSort(column)}
                className="flex cursor-pointer items-center gap-1 font-bold"
            >
                {label}
                <SortIcon column={column} activeColumn={sortColumn} direction={sortDirection} />
            </button>
        );
    }

    return (
        <div className="relative my-3 h-100 w-full overflow-auto rounded-lg border bg-slate-50">
            {/* --- STICKY HEADER --- */}
            <div className="sticky top-0 grid grid-cols-5 justify-items-center bg-white font-bold">
                <SortableHeader column="user_name" label="Name" />
                <SortableHeader column="dates" label="Date" />
                <div className="font-bold">Partial day</div>
                <SortableHeader column="leave_reason" label="Leave reason" />
                <div></div>
            </div>
            {sorted.map((LR) => (
                <div key={LR.id} className="my-2 grid grid-cols-5 justify-items-center gap-2 rounded-lg border border-mallard-green p-2">
                    <div>{LR.user_name}</div>
                    <div>
                        {formatDatesRange(LR.dates)}
                    </div>
                    <div>
                        {LR.start_time
                            ? `${LR.start_time} - ${LR.end_time}`
                            : ''}
                    </div>
                    <div>{LR.leave_reason}</div>
                    <div>
                        <PrimaryLink href={`/manageleaverequests/${LR.id}`}>
                                                            Review
                                                        </PrimaryLink>
                    </div>
                </div>
            ))}
        </div>
    );
}
