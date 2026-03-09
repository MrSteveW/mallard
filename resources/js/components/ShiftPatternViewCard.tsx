import { getShiftBgColor } from '@/constants/shiftBgColors';

interface ShiftPattern {
    day: number;
    shift_type: string;
    start_time: string;
    end_time: string;
}

interface ViewCardProps {
    shift?: ShiftPattern;
}

export default function ShiftPatternViewCard({ shift }: ViewCardProps) {
    const bgColor = getShiftBgColor(shift?.shift_type, shift?.day);

    return (
        <div>
            <div
                className={`${bgColor} flex h-8 flex-col items-center justify-center border-r border-b p-2 text-[11px]`}
            >
                {shift?.shift_type === 'Off' ? (
                    'Off'
                ) : (
                    <div className="flex flex-row">
                        <div>{shift?.start_time}</div>
                        <div>-</div>
                        <div>{shift?.end_time}</div>
                    </div>
                )}
            </div>
        </div>
    );
}
