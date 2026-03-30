import { useDraggable } from '@dnd-kit/core';
import type { Duty } from '@/types';

type DutyCardProps = {
    duty: Duty;
    getUserDetails: (userId: number) => { name: string; grade: string };
};

export function DutyCreateCard({ duty, getUserDetails }: DutyCardProps) {
    const userDetails = getUserDetails(duty.user_id);
    const { attributes, listeners, setNodeRef, transform } = useDraggable({
        id: duty.id,
    });

    const style = transform
        ? {
              transform: `translate(${transform.x}px, ${transform.y}px)`,
          }
        : undefined;

    return (
        <div
            ref={setNodeRef}
            {...listeners}
            {...attributes}
            className="cursor-grab rounded-lg bg-white px-2 shadow-sm hover:shadow-md"
            style={style}
        >
            <div className="flex">
                <div>{userDetails.name}</div>
                <div>{userDetails.grade}</div>
            </div>
        </div>
    );
}
