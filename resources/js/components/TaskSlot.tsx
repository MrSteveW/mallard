import { useDroppable } from '@dnd-kit/core';
import { memo } from 'react';
import type { Duty, Task } from '@/types';
import { DutyCard } from './DutyCreateCard';

type TaskSlotProps = {
    task: Task;
    duty?: Duty | null;
    getUserDetails: (userId: number) => { name: string; grade: string };
};

// React.memo prevents re-render if props haven't changed
export const TaskSlot = memo(function TaskSlot({
    task,
    duty,
    getUserDetails,
}: TaskSlotProps) {
    const { setNodeRef } = useDroppable({
        id: task.id,
    });

    return (
        <div className="flex h-40 w-80 flex-col rounded-lg bg-neutral-500 p-4">
            <h2 className="mb-4 font-semibold text-neutral-100">{task.name}</h2>
            <div ref={setNodeRef} className="flex flex-1 flex-col gap-4">
                {duty && (
                    <DutyCard duty={duty} getUserDetails={getUserDetails} />
                )}
            </div>
        </div>
    );
});
