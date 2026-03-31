import type { EventContentArg, EventSourceFuncArg } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import FullCalendar from '@fullcalendar/react';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { useRef } from 'react';
import DutyIndexCard from '@/components/DutyIndexCard';
import AppLayout from '@/layouts/app-layout';
import { mapToDutyEvent } from '@/lib/mapToDutyEvent';

export default function Index() {
    const calendarRefresh = useRef<FullCalendar>(null);
    function handleEventSelect() {}
    return (
        <AppLayout>
            <Head title="Duties" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <FullCalendar
                    ref={calendarRefresh}
                    plugins={[dayGridPlugin]}
                    locale="en-gb"
                    dayHeaderFormat={{
                        weekday: 'long',
                        day: 'numeric',
                        month: 'numeric',
                        omitCommas: true,
                    }}
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
