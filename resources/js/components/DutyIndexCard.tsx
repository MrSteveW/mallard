import { getShiftBgColor } from '@/constants/shiftBgColors';
import type { DutyEvent } from '@/types.ts';

interface IndexCardProps {
    dutyEvent: DutyEvent;
    handleEventSelect: (dutyEvent: DutyEvent) => void;
}

export default function DutyIndexCard({
    dutyEvent,
    handleEventSelect,
}: IndexCardProps) {
    const bgColor = getShiftBgColor(dutyEvent?.shift_type);
    return (
        <div
            onClick={() => handleEventSelect(dutyEvent)}
            className={`${bgColor} "m-1 w-full cursor-pointer rounded-xl border p-2 hover:bg-amber-200`}
        >
            <div className="flex">
                <div className="">{dutyEvent.user_name}</div>
                <div className="">{dutyEvent.grade}</div>
            </div>
            <div className="flex">
                <div>
                    <div>{dutyEvent?.start_time}</div>
                </div>
                <div>:</div>
                <div>{dutyEvent?.end_time}</div>
            </div>
        </div>
    );
}
