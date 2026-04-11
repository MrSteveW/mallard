import type { EventContentArg, EventSourceFuncArg } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import FullCalendar from '@fullcalendar/react';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { useRef } from 'react';
import DutyDayHeader from '@/components/DutyDayHeader';
import DutyIndexCard from '@/components/DutyIndexCard';
import AppLayout from '@/layouts/app-layout';
import { mapToDutyEvent } from '@/lib/mapToDutyEvent';
import type { CalendarNote } from '@/types.ts';

interface DashboardProps {
    calendarNotes: CalendarNote[];
}

export default function Index({ calendarNotes }: DashboardProps) {
    const calendarNotesByDate: Record<string, CalendarNote[]> =
        calendarNotes.reduce<Record<string, CalendarNote[]>>((acc, note) => {
            if (!acc[note.date]) {
                acc[note.date] = [];
            }
            acc[note.date].push(note);
            return acc;
        }, {});

    const calendarRefresh = useRef<FullCalendar>(null);
    function handleEventSelect() {}
    return (
        <AppLayout>
            <Head title="Duties" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <FullCalendar
                    height="calc(100vh - 150px)"
                    ref={calendarRefresh}
                    plugins={[dayGridPlugin]}
                    locale="en-gb"
                    initialView="dayGridWeek"
                    weekNumberCalculation={'ISO'}
                    events={async (fetchInfo: EventSourceFuncArg) => {
                        const response = await axios.get('/api/duties', {
                            params: {
                                start: fetchInfo.startStr,
                                end: fetchInfo.endStr,
                            },
                        });
                        return response.data;
                    }}
                    dayHeaderContent={(arg) => (
                        <DutyDayHeader
                            date={arg.date}
                            calendarNotes={calendarNotesByDate}
                            editable={false}
                        />
                    )}
                    eventContent={(arg: EventContentArg) => (
                        <DutyIndexCard
                            dutyEvent={mapToDutyEvent(arg)}
                            handleEventSelect={handleEventSelect}
                        />
                    )}
                />
            </div>
        </AppLayout>
    );
}
