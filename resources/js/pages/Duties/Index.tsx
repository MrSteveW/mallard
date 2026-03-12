import type { EventContentArg, EventSourceFuncArg } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import FullCalendar from '@fullcalendar/react';
import { Head } from '@inertiajs/react';
import { useState, useRef } from 'react';
import DutyDialog from '@/components/DutyDialog';
import DutyIndexCard from '@/components/DutyIndexCard';
import AppLayout from '@/layouts/app-layout';
import { jsonFetch } from '@/lib/api';
import { mapToDutyEvent } from '@/lib/mapToDutyEvent';
import type { DutyEvent } from '@/types.ts';

export default function Index() {
    const [selectedEvent, setSelectedEvent] = useState<DutyEvent | null>(null);
    const [isDialogOpen, setIsDialogOpen] = useState<boolean>(false);
    const calendarRefresh = useRef<FullCalendar>(null);

    const handleRefresh = () => {
        calendarRefresh.current?.getApi().refetchEvents();
    };

    function handleEventSelect(dutyEvent: DutyEvent) {
        setSelectedEvent(dutyEvent);
        setIsDialogOpen(true);
    }

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
                        const duties: DutyEvent[] = await jsonFetch(
                            `/duties?start=${fetchInfo.startStr}&end=${fetchInfo.endStr}`,
                        );
                        return duties;
                    }}
                    eventContent={(arg: EventContentArg) => (
                        <DutyIndexCard
                            dutyEvent={mapToDutyEvent(arg)}
                            handleEventSelect={handleEventSelect}
                        />
                    )}
                />
                {selectedEvent && (
                    <DutyDialog
                        key={selectedEvent?.id}
                        initialEvent={selectedEvent}
                        isDialogOpen={isDialogOpen}
                        onClose={setIsDialogOpen}
                        action={`/duties/${selectedEvent.id}`}
                        method="patch"
                        onSuccess={handleRefresh}
                    />
                )}
            </div>
        </AppLayout>
    );
}
