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
    const isCancelled = !!dutyEvent.cancelled_at;
    return (
        <div
            onClick={() => handleEventSelect(dutyEvent)}
            className={`${bgColor} m-1 w-full cursor-pointer rounded-xl border p-1 text-xs hover:bg-amber-200 sm:text-sm md:p-2 md:text-base ${isCancelled ? 'opacity-50' : ''}`}
        >
            <div className="flex">
                <div className={isCancelled ? 'line-through' : ''}>{dutyEvent.user_name}</div>
                <div className="hidden md:block">{dutyEvent.grade}</div>
            </div>
            <div className="w-full text-xs sm:text-sm">
                {isCancelled ? (
                    <span>{dutyEvent.cancel_reason ?? 'Cancelled'}</span>
                ) : (
                    <>{dutyEvent?.start_time}-<wbr />{dutyEvent?.end_time}</>
                )}
            </div>
        </div>
    );
}
