import { useForm, Link } from '@inertiajs/react';
import { useState } from 'react';
import ShiftPatternCard from '@/components/ShiftPatternCard';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Label } from '@/components/ui/label';
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
    userOptions: ShiftPatternUser[];
    totalDays: number;
    initialData?: ShiftPatternDay[];
    action: string;
    method?: 'post' | 'patch';
}

export default function ShiftPatternForm({
    userOptions,
    totalDays,
    initialData,
    action,
    method = 'post',
}: ShiftPatternProps) {
    const { data, setData, post, patch, processing, errors } = useForm({
        shiftArray:
            initialData ??
            Array.from({ length: totalDays }, (_, i) => ({
                user_id: '' as number | '',
                day: i + 1,
                shift_type: 'Off',
                start_time: '',
                end_time: '',
            })),
    });

    // Seed the selected user from initialData if present
    const [selectedUser, setSelectedUser] = useState<number | undefined>(() => {
        const id = initialData?.[0]?.user_id;
        return typeof id === 'number' ? id : undefined;
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
        { length: Math.ceil(totalDays / 7) },
        (_, weekIndex) =>
            data.shiftArray.slice(weekIndex * 7, weekIndex * 7 + 7),
    );

    const handleUserChange = (v: string) => {
        const id = parseInt(v, 10);
        setSelectedUser(id);
        setData(
            'shiftArray',
            data.shiftArray.map((shift) => ({ ...shift, user_id: id })),
        );
    };

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
                {/* <div className="text-xs">{JSON.stringify(data.shiftArray)}</div> */}
                <div className="flex w-full border bg-gray-50 px-8 py-4">
                    <div className="flex w-30 flex-col gap-2">
                        <Label>Select user</Label>
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="outline">
                                    {userOptions.find(
                                        (user) => user.id === selectedUser,
                                    )?.name || 'Select User'}
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent>
                                <DropdownMenuRadioGroup
                                    value={selectedUser?.toString()}
                                    onValueChange={handleUserChange}
                                >
                                    {userOptions.map((user) => (
                                        <DropdownMenuRadioItem
                                            key={user.id}
                                            value={user.id.toString()}
                                        >
                                            {user.name}
                                        </DropdownMenuRadioItem>
                                    ))}
                                </DropdownMenuRadioGroup>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                    <div className="mx-10 mt-4 flex flex-row items-center">
                        <Button type="submit" disabled={processing}>
                            {method === 'post' ? 'Create' : 'Save'}
                        </Button>
                        <Button variant="outline" asChild>
                            <Link href="/shiftpatterns">Cancel</Link>
                        </Button>
                    </div>
                </div>

                <div className="flex h-screen w-screen flex-row gap-2 overflow-auto border bg-gray-200">
                    {selectedUser &&
                        weeks.map((week, weekIndex) => (
                            <div key={weekIndex}>
                                <h3>Week {weekIndex + 1}</h3>
                                <div>
                                    {week.map((day, dayIndex) => {
                                        const index = weekIndex * 7 + dayIndex;
                                        return (
                                            <ShiftPatternCard
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
