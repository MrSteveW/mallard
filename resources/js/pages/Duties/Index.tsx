import type {
    DatesSetArg,
    EventContentArg,
    EventSourceFuncArg,
} from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import FullCalendar from '@fullcalendar/react';
import { Head, router } from '@inertiajs/react';
import axios from 'axios';
import { useState, useRef } from 'react';
import DutyDialog from '@/components/DutyDialog';
import DutyIndexCard from '@/components/DutyIndexCard';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { mapToDutyEvent } from '@/lib/mapToDutyEvent';
import type { DutyEvent, AssignableUser } from '@/types.ts';

interface IndexProps {
    users: AssignableUser[];
    generatedMonths: string[];
}

export default function Index({ users, generatedMonths }: IndexProps) {
    const [selectedEvent, setSelectedEvent] = useState<DutyEvent | null>(null);
    const [isDialogOpen, setIsDialogOpen] = useState<boolean>(false);
    const [selectedMonth, setSelectedMonth] = useState<string>('');
    const calendarRef = useRef<FullCalendar>(null);

    function handleEventSelect(dutyEvent: DutyEvent) {
        setSelectedEvent(dutyEvent);
        setIsDialogOpen(true);
    }

    function handleCreateClick() {
        setSelectedEvent(null);
        setIsDialogOpen(true);
    }

    function handleDateSet(dateInfo: DatesSetArg) {
        const start = dateInfo.start;
        const end = new Date(dateInfo.end);
        end.setDate(end.getDate() - 1);
        const target = end.getMonth() != start.getMonth() ? end : start;
        const year = target.getFullYear();
        const month = String(target.getMonth() + 1).padStart(2, '0');
        setSelectedMonth(year + '-' + month);
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
                    {!generatedMonths.includes(selectedMonth) && (
                        <Button
                            onClick={() =>
                                router.post(
                                    '/duties/generate',
                                    { month: selectedMonth },
                                    {
                                        onSuccess: () =>
                                            calendarRef.current
                                                ?.getApi()
                                                .refetchEvents(),
                                    },
                                )
                            }
                            className="hover:mallard-green/80 rounded-md bg-mallard-green px-3 py-2 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                        >
                            Generate{' '}
                            {selectedMonth
                                ? new Date(
                                      selectedMonth + '-01',
                                  ).toLocaleString('default', { month: 'long' })
                                : ''}{' '}
                            Duties
                        </Button>
                    )}
                </div>

                <FullCalendar
                    ref={calendarRef}
                    datesSet={handleDateSet}
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
