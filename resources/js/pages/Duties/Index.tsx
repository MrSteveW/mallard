import type { EventContentArg, EventSourceFuncArg } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import FullCalendar from '@fullcalendar/react';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { useState } from 'react';
import DutyDialog from '@/components/DutyDialog';
import DutyIndexCard from '@/components/DutyIndexCard';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { mapToDutyEvent } from '@/lib/mapToDutyEvent';
import type { DutyEvent, AssignableUser } from '@/types.ts';

interface IndexProps {
    users: AssignableUser[];
}

export default function Index({ users }: IndexProps) {
    const [selectedEvent, setSelectedEvent] = useState<DutyEvent | null>(null);
    const [isDialogOpen, setIsDialogOpen] = useState<boolean>(false);

    function handleEventSelect(dutyEvent: DutyEvent) {
        setSelectedEvent(dutyEvent);
        setIsDialogOpen(true);
    }

    function handleCreateClick() {
        setSelectedEvent(null);
        setIsDialogOpen(true);
    }

    return (
        <AppLayout>
            <Head title="Duties" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div>
                    <Button
                        onClick={handleCreateClick}
                        className="hover:mallard-green/80 rounded-md bg-mallard-green px-3 py-2 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                    >
                        + Duty
                    </Button>
                </div>

                <FullCalendar
                    events={async (fetchInfo: EventSourceFuncArg) => {
                        const response = await axios.get('/api/duties', {
                            params: {
                                start: fetchInfo.startStr,
                                end: fetchInfo.endStr,
                            },
                        });
                        return response.data;
                    }}
                    weekNumberCalculation={'ISO'}
                    plugins={[dayGridPlugin]}
                    locale="en-gb"
                    dayHeaderFormat={{
                        weekday: 'long',
                        day: 'numeric',
                        month: 'numeric',
                        omitCommas: true,
                    }}
                    initialView="dayGridWeek"
                    eventContent={(arg: EventContentArg) => (
                        <DutyIndexCard
                            dutyEvent={mapToDutyEvent(arg)}
                            handleEventSelect={handleEventSelect}
                        />
                    )}
                />
                <DutyDialog
                    key={selectedEvent?.id ?? 'create'}
                    initialEvent={selectedEvent}
                    users={users}
                    isDialogOpen={isDialogOpen}
                    onClose={setIsDialogOpen}
                    action={
                        selectedEvent
                            ? `/duties/${selectedEvent.id}`
                            : '/duties'
                    }
                    method={selectedEvent ? 'patch' : 'post'}
                />
            </div>
        </AppLayout>
    );
}
