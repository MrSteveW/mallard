import { Head } from '@inertiajs/react';
import { Link } from '@inertiajs/react';
import React from 'react';
import AppLayout from '@/layouts/app-layout';

interface ShiftPattern {
    day: number;
    status: string;
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
    days: Day[];
}

export default function Index({ shiftpatterns, days }: Props) {
    const gridTemplateColumns = `80px 120px repeat(${shiftpatterns.length}, 150px)`;
    return (
        <AppLayout>
            <Head title="Shift patterns" />
            <div className="my-3 flex flex-row">
                <div>
                    <Link
                        href="/shiftpatterns/create"
                        className="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                    >
                        + Shift pattern
                    </Link>
                </div>
            </div>
            {/* <div>{JSON.stringify(shiftpatterns)}</div> */}
            <div className="relative h-[calc(100vh-160px)] w-full overflow-auto rounded-lg border bg-slate-50">
                <div className="grid" style={{ gridTemplateColumns }}>
                    {/* --- STICKY HEADER --- */}
                    <div className="sticky top-0 z-20 flex items-center justify-center border-r border-b bg-amber-200 p-2 font-bold">
                        Day
                    </div>
                    <div className="sticky top-0 z-20 flex items-center justify-center border-r border-b bg-amber-200 p-2 font-bold">
                        Day Name
                    </div>

                    {shiftpatterns.map((user) => (
                        <div
                            key={user.user_id}
                            className="sticky top-0 z-20 border-r border-b bg-amber-100 p-2 text-center font-bold"
                        >
                            {user.user_name}
                        </div>
                    ))}

                    {/* --- DATA ROWS --- */}
                    {days.map((day) => (
                        <React.Fragment key={day.number}>
                            {/* Day Number Column */}
                            <div className="sticky left-0 z-10 flex items-center justify-center border-r border-b bg-green-100 p-2">
                                {day.number}
                            </div>

                            {/* Day Name Column */}
                            <div className="sticky left-20 z-10 flex items-center border-r border-b bg-green-50 p-2">
                                {day.name}
                            </div>

                            {/* User Shift Cells */}
                            {shiftpatterns.map((user) => {
                                // Find the specific shift for this day and user
                                const shift = user.shift_pattern.find(
                                    (s) => s.day === day.number,
                                );
                                const isOnDuty = shift?.status === 'On Duty';

                                return (
                                    <div
                                        key={`${user.user_id}-${day.number}`}
                                        className="flex h-16 flex-col items-center justify-center border-r border-b p-2 text-xs"
                                    >
                                        <span>{shift?.status}</span>
                                        {isOnDuty && shift.start_time && (
                                            <span className="text-xs">
                                                {new Date(
                                                    shift.start_time,
                                                ).toLocaleTimeString([], {
                                                    hour: '2-digit',
                                                    minute: '2-digit',
                                                })}
                                            </span>
                                        )}
                                    </div>
                                );
                            })}
                        </React.Fragment>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
