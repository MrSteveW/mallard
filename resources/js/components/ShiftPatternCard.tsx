import { usePage } from '@inertiajs/react';
import TimeSelect from '@/components/TimeSelect';
import { Label } from '@/components/ui/label';
import type { TimeOptions } from '@/types';
import type { ShiftTypeOption } from '@/types';

interface ShiftDay {
    user_id: number | '';
    day: number;
    shift_type: string;
    start_time: string;
    end_time: string;
}

interface Props {
    day: ShiftDay;
    index: number;
    label: string;
    onChange: (index: number, fields: Partial<ShiftDay>) => void;
    errors: {
        shift_type?: string;
        start_time?: string;
        end_time?: string;
    };
}

export default function ShiftPatternCard({
    day,
    index,
    label,
    onChange,
    errors,
}: Props) {
    // Use Inertia Page Props to get timeOptions and ShiftTypeOptions
    const { timeOptions } = usePage().props as unknown as {
        timeOptions: TimeOptions;
    };
    const { shiftTypeOptions } = usePage().props as unknown as {
        shiftTypeOptions: ShiftTypeOption[];
    };

    // Changes shift_type field and changes start & end time selections
    const handleShiftTypeChange = (value: string) => {
        const selected = shiftTypeOptions.find((opt) => opt.value === value);

        if (selected && value !== 'Off') {
            onChange(index, {
                shift_type: value,
                start_time: selected.start_time ?? '',
                end_time: selected.end_time ?? '',
            });
        } else {
            onChange(index, {
                shift_type: value,
                start_time: '',
                end_time: '',
            });
        }
    };

    // Conditionally colour background
    const shiftTypeColors: Record<string, string> = {
        Off: 'bg-white',
        Early: 'bg-amber-200',
        Late: 'bg-green-300',
        Night: 'bg-blue-200',
    };

    const bgColor = shiftTypeColors[day.shift_type] ?? 'bg-white';

    return (
        <div className={`${bgColor} m-1 w-50 rounded-xl border p-1`}>
            <div className="text-sm">{label}</div>
            {/* Select Shift Type */}
            <select
                value={day.shift_type}
                onChange={(e) => handleShiftTypeChange(e.target.value)}
                className="text-sm"
            >
                {shiftTypeOptions.map((opt) => (
                    <option key={opt.value} value={opt.value}>
                        {opt.label}
                    </option>
                ))}
            </select>

            {errors.shift_type && <p>{errors.shift_type}</p>}

            {/* Conditionally render Start & End DropDowns */}
            {day.shift_type !== 'Off' && (
                <div className="flex w-full flex-row justify-around">
                    <div>
                        <Label>Start time</Label>
                        <TimeSelect
                            name="start_time"
                            value={day.start_time}
                            options={timeOptions}
                            onChange={(v) => onChange(index, { start_time: v })}
                        />
                        {errors.start_time && <p>{errors.start_time}</p>}
                    </div>
                    <div>
                        <Label>End time</Label>
                        <TimeSelect
                            name="end_time"
                            value={day.end_time}
                            options={timeOptions}
                            onChange={(v) => onChange(index, { end_time: v })}
                        />
                        {errors.end_time && <p>{errors.end_time}</p>}
                    </div>
                </div>
            )}
        </div>
    );
}
