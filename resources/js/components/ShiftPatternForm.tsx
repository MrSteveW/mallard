import { useForm, Link } from '@inertiajs/react';
import ShiftPatternEditCard from '@/components/ShiftPatternEditCard';
import { Button } from '@/components/ui/button';
import type { ShiftPatternUser, ShiftPatternDay } from '@/types';

const DAY_NAMES = [
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
    'Sunday',
];

interface ShiftPatternProps {
    user: ShiftPatternUser;
    initialPattern: ShiftPatternDay[];
    action: string;
    method?: 'post' | 'patch';
}

export default function ShiftPatternForm({
    user,
    initialPattern,
    action,
    method = 'post',
}: ShiftPatternProps) {
    const { data, setData, post, patch, processing, errors } = useForm({
        shiftArray: initialPattern,
    });

    const handleDayChange = (
        dayIndex: number,
        fields: Partial<ShiftPatternDay>,
    ) => {
        setData(
            'shiftArray',
            data.shiftArray.map((d, i) =>
                i === dayIndex ? { ...d, ...fields } : d,
            ),
        );
    };

    const weeks = Array.from(
        { length: Math.ceil(data.shiftArray.length / 7) },
        (_, weekIndex) =>
            data.shiftArray.slice(weekIndex * 7, weekIndex * 7 + 7),
    );

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (method === 'patch') return patch(action);
        post(action);
    };

    return (
        <form onSubmit={handleSubmit} className="flex flex-col gap-6">
            <div className="grid gap-1">
                <div className="text-xs text-red-500">
                    {Object.entries(errors).map(([key, value]) => (
                        <div key={key}>
                            {key}: {value}
                        </div>
                    ))}
                </div>
                <div className="flex w-full items-center border bg-gray-50 p-2">
                    <div className="ml-100 text-lg">
                        Edit Shift Pattern for {user.name}
                    </div>
                    <div className="ml-20 flex flex-row items-center">
                        <Button type="submit" disabled={processing}>
                            Save
                        </Button>
                        <Button variant="outline" asChild>
                            <Link href="/shiftpatterns">Cancel</Link>
                        </Button>
                    </div>
                </div>

                <div className="flex h-screen w-screen flex-row gap-2 overflow-auto border bg-gray-200">
                    {weeks.map((week, weekIndex) => (
                        <div key={weekIndex}>
                            <h3>Week {weekIndex + 1}</h3>
                            <div>
                                {week.map((day, dayIndex) => {
                                    const index = weekIndex * 7 + dayIndex;
                                    return (
                                        <ShiftPatternEditCard
                                            key={day.day}
                                            day={day}
                                            index={index}
                                            label={`${DAY_NAMES[dayIndex]}`}
                                            onChange={handleDayChange}
                                            errors={{
                                                shift_type:
                                                    errors[
                                                        `shiftArray.${index}.shift_type`
                                                    ],
                                                start_time:
                                                    errors[
                                                        `shiftArray.${index}.start_time`
                                                    ],
                                                end_time:
                                                    errors[
                                                        `shiftArray.${index}.end_time`
                                                    ],
                                            }}
                                        />
                                    );
                                })}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </form>
    );
}
