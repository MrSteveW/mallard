import { Head } from '@inertiajs/react';
import { Link } from '@inertiajs/react';
import React from 'react';
import ShiftPatternViewCard from '@/components/ShiftPatternViewCard';
import AppLayout from '@/layouts/app-layout';

interface ShiftPattern {
    day: number;
    shift_type: string;
    start_time: string;
    end_time: string;
}
interface UserShiftPattern {
    user_id: number;
    user_name: string;
    shift_pattern: ShiftPattern[];
}

interface Day {
    number: number;
    name: string;
}

interface Props {
    shiftpatterns: UserShiftPattern[];
    dayNames: Day[];
}

export default function Index({ shiftpatterns, dayNames }: Props) {
    const gridTemplateColumns = `40px 80px repeat(${shiftpatterns.length}, 70px)`;
    return (
        <AppLayout>
            <Head title="Shift patterns" />
            <div className="relative my-3 h-[calc(100vh-100px)] w-full overflow-auto rounded-lg border bg-slate-50">
                <div className="grid" style={{ gridTemplateColumns }}>
                    {/* --- STICKY HEADER --- */}
                    <div className="sticky top-0 z-20 flex items-center justify-center border-r border-b bg-white"></div>
                    <div className="sticky top-0 z-20 flex items-center justify-center border-r border-b bg-white"></div>

                    {shiftpatterns.map((user) => (
                        <div
                            key={user.user_id}
                            className="sticky top-0 z-20 border-r border-b bg-amber-100 p-2 text-center text-sm font-bold hover:text-mallard-green"
                        >
                            <Link href={`/shiftpatterns/${user.user_id}/edit`}>
                                {user.user_name}
                            </Link>
                        </div>
                    ))}

                    {/* --- DATA ROWS --- */}
                    {dayNames.map((day) => (
                        <React.Fragment key={day.number}>
                            {/* Day Number Column */}
                            <div className="sticky z-10 flex items-center justify-center border-r border-b bg-green-100 text-xs">
                                {day.number}
                            </div>

                            {/* Day Name Column */}
                            <div className="sticky z-10 flex items-center border-r border-b bg-green-50 p-2 text-xs">
                                {day.name}
                            </div>

                            {/* User Shift Cells */}
                            {shiftpatterns.map((user) => {
                                // Find the specific shift for this day and user
                                const shift = user.shift_pattern.find(
                                    (s) => s.day === day.number,
                                );

                                return (
                                    <ShiftPatternViewCard
                                        key={`${user.user_id}-${day.number}`}
                                        shift={shift}
                                    />
                                );
                            })}
                        </React.Fragment>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
