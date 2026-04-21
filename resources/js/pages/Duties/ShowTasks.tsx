import { DndContext, type DragEndEvent } from '@dnd-kit/core';
import { Head, Link, router } from '@inertiajs/react';
import { useCallback, useMemo, useState } from 'react';
import { AvailableColumn } from '@/components/AvailableColumn';
import { TaskSlot } from '@/components/TaskSlot';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { AssignableUser, Duty, Task } from '@/types';

interface ShowTasksProps {
    date: string;
    duties?: Duty[];
    users?: AssignableUser[];
    tasks?: Task[];
}

export default function ShowTasks({ date, duties: initialDutiesProp, users, tasks }: ShowTasksProps) {
    const [duties, setDuties] = useState<Duty[]>(initialDutiesProp ?? []);

    const handleDragEnd = useCallback((event: DragEndEvent) => {
        const { active, over } = event;

        if (!over) return;

        const dutyId = active.id as number;
        const newTaskId = over.id === 'unassigned' ? null : (over.id as number);

        setDuties((prevDuties) =>
            prevDuties.map((duty) => {
                if (duty.id === dutyId) {
                    return { ...duty, task_id: newTaskId };
                }
                if (newTaskId !== null && duty.task_id === newTaskId) {
                    return { ...duty, task_id: null };
                }
                return duty;
            }),
        );
    }, []);

    const patchPayload = () => ({
        duties: duties.map((duty) => ({
            id: duty.id,
            task_id: duty.task_id,
        })),
    });

    const handleSave = () => {
        router.patch(`/duties/${date}/tasks`, patchPayload());
    };

    const handleSaveAndClose = () => {
        router.patch(`/duties/${date}/tasks`, patchPayload(), {
            onSuccess: () => router.visit('/duties'),
        });
    };

    const getUserDetails = useCallback(
        (userId: number): { name: string; grade: string } => {
            const user = users?.find((user) => user.id === userId);
            return {
                name: user?.name || 'Unknown',
                grade: user?.grade || '',
            };
        },
        [users],
    );

    const unassignedDuties = useMemo(
        () => duties.filter((duty) => duty.task_id === null),
        [duties],
    );

    return (
        <AppLayout>
            <Head title="Duties" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="relative flex items-center bg-mallard-orange px-4 py-2">
                    <div className="absolute left-1/2 -translate-x-1/2">
                        Assign tasks for{' '}
                        {new Date(date).toLocaleDateString('en-GB', {
                            weekday: 'short',
                            day: 'numeric',
                            month: 'short',
                        })}
                    </div>
                    <div className="ml-auto flex gap-2">
                        <Button type="button" onClick={handleSave}>
                            Save
                        </Button>
                        <Button type="button" variant="outline" onClick={handleSaveAndClose}>
                            Save & Close
                        </Button>
                        <Button variant="outline">
                            <Link href="/duties">Cancel</Link>
                        </Button>
                    </div>
                </div>

                <DndContext onDragEnd={handleDragEnd}>
                    <div className="flex gap-8 p-4">
                        <AvailableColumn
                            duties={unassignedDuties}
                            getUserDetails={getUserDetails}
                        />
                        {tasks?.map((task) => {
                            const assignedDuty = duties.find(
                                (duty) => duty.task_id === task.id,
                            );
                            return (
                                <TaskSlot
                                    key={task.id}
                                    task={task}
                                    duty={assignedDuty}
                                    getUserDetails={getUserDetails}
                                />
                            );
                        })}
                    </div>
                </DndContext>
            </div>
        </AppLayout>
    );
}
