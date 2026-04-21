import { useDroppable } from '@dnd-kit/core';
import { memo } from 'react';
import { DutyCreateCard } from '@/components/DutyCreateCard';
import type { Duty } from '@/types';

interface AvailableColumnProps {
    duties: Duty[];
    getUserDetails: (userId: number) => { name: string; grade: string };
}

// React.memo prevents re-render if props haven't changed
export const AvailableColumn = memo(function AvailableColumn({
    duties,
    getUserDetails,
}: AvailableColumnProps) {
    const { setNodeRef } = useDroppable({
        id: 'unassigned',
    });

    return (
        <div className="flex h-screen w-80 flex-col rounded-lg bg-mallard-green p-4">
            <h2 className="font-semibold text-neutral-100">Available users</h2>
            <div ref={setNodeRef} className="flex flex-1 flex-col gap-1">
                {duties.map((duty) => (
                    <DutyCreateCard
                        key={duty.id}
                        duty={duty}
                        getUserDetails={getUserDetails}
                    />
                ))}
            </div>
        </div>
    );
});
