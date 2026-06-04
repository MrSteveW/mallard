import { Head, useForm, usePage } from '@inertiajs/react';
import React, { useState } from 'react';
import DatePicker, { getAllDatesInRange } from 'react-multi-date-picker';
import type { DateObject } from 'react-multi-date-picker';
import { store } from '@/actions/App/Http/Controllers/LeaveRequestController';
import TimeSelect from '@/components/TimeSelect';
import { Label } from '@/components/ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/app-layout';
import { calculateDuration } from '@/lib/utils';
import type { SharedData } from '@/types/index';
import type { LeaveOption, TimeOptions } from '@/types.ts';
import { PrimaryLink } from '@/components/ui/primary-link';

type Mode = 'partial_day' | 'multiple_days' | 'range';

interface CreateProps {
    dutyDates: string[];
}

function createMapDays(dutyDates: string[]) {
    return ({ date }: { date: { format: (fmt: string) => string } }) => {
        const formattedDate = date.format('YYYY-MM-DD');
        if (!dutyDates.includes(formattedDate)) {
            return { disabled: true, style: { color: '#ccc' } };
        }
    };
}

export default function Create({ dutyDates }: CreateProps) {
    const [submitted, setSubmitted] = useState(false);
    const [singleValue, setSingleValue] = useState<DateObject | null>(null);
    const [multiValue, setMultiValue] = useState<DateObject[]>([]);
    const [rangeValue, setRangeValue] = useState<DateObject[]>([]);

    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);

    const { leaveOptions } = usePage().props as unknown as {
        leaveOptions: LeaveOption[];
    };
    const { timeOptions } = usePage().props as unknown as {
        timeOptions: TimeOptions;
    };

    const { auth } = usePage<SharedData>().props;

    const { data, setData, post, processing } = useForm({
        leave_reason: '',
        mode: 'multiple_days' as Mode,
        dates: [] as string[],
        start_time: '',
        end_time: '',
        notes: '',
    });

    function handleModeChange(mode: Mode) {
        setSingleValue(null);
        setMultiValue([]);
        setRangeValue([]);
        setData((prev) => ({
            ...prev,
            mode,
            dates: [],
            start_time: '',
            end_time: '',
        }));
    }

    function handleSingleDateChange(selectedDate: DateObject) {
        setSingleValue(selectedDate);
        setData(
            'dates',
            selectedDate ? [selectedDate.format('YYYY-MM-DD')] : [],
        );
    }

    function handleMultiDateChange(selectedDates: DateObject[]) {
        setMultiValue(selectedDates);
        setData(
            'dates',
            selectedDates.map((d) => d.format('YYYY-MM-DD')),
        );
    }

    function handleRangeDateChange(selectedDates: DateObject[]) {
        setRangeValue(selectedDates);
        const allInRange = getAllDatesInRange(selectedDates).map((date) =>
            (date as DateObject).format('YYYY-MM-DD'),
        );
        setData(
            'dates',
            dutyDates.filter((d) => allInRange.includes(d)),
        );
    }

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        setSubmitted(true);
        post(store.url());
    }

    return (
        <AppLayout>
            <Head title="Leave request" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
                <form onSubmit={handleSubmit} className="flex flex-col gap-6">
                    {/* Leave reason */}
                    <section className="flex flex-col gap-2">
                        <Label>Reason for absence</Label>
                        <select
                            className="w-fit cursor-pointer appearance-none rounded-lg border border-input bg-background p-1 outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            value={data.leave_reason}
                            onChange={(
                                e: React.ChangeEvent<HTMLSelectElement>,
                            ) => setData('leave_reason', e.target.value)}
                        >
                            <option value="" disabled>
                                Please select
                            </option>
                            {leaveOptions.map((opt) => (
                                <option key={opt.value} value={opt.value}>
                                    {opt.value}
                                </option>
                            ))}
                        </select>
                        {submitted && !data.leave_reason && (
                            <p className="text-xs text-red-500">
                                Reason is required.
                            </p>
                        )}
                    </section>

                    {/* Type of absence */}
                    <Tabs
                        value={data.mode}
                        onValueChange={(value) =>
                            handleModeChange(value as Mode)
                        }
                    >
                        <Label>Type of absence</Label>
                        <TabsList>
                            <TabsTrigger value="partial_day">
                                Part of 1 day
                            </TabsTrigger>
                            <TabsTrigger value="multiple_days">
                                Single or multiple days
                            </TabsTrigger>
                            <TabsTrigger value="range">
                                Range of consecutive days
                            </TabsTrigger>
                        </TabsList>

                        <TabsContent value="partial_day">
                            <section className="flex flex-col gap-3">
                                <Label>Select date</Label>
                                <DatePicker
                                    value={singleValue}
                                    minDate={tomorrow}
                                    format="DD-MM-YYYY"
                                    onChange={handleSingleDateChange}
                                    render={(_value, openCalendar) => (
                                        <button
                                            type="button"
                                            className="w-fit cursor-pointer rounded border border-black px-3 py-1"
                                            onClick={openCalendar}
                                        >
                                            Select date
                                        </button>
                                    )}
                                    mapDays={createMapDays(dutyDates)}
                                />
                                {submitted && data.dates.length === 0 && (
                                    <p className="text-xs text-red-500">
                                        Date is required.
                                    </p>
                                )}
                                {data.dates.length > 0 && (
                                    <p className="text-sm">
                                        {data.dates.join(', ')}
                                    </p>
                                )}
                                <Label>Leave start and end</Label>
                                <div className="flex items-center gap-3">
                                    <TimeSelect
                                        name="start_time"
                                        value={data.start_time}
                                        options={timeOptions}
                                        onChange={(value: string) =>
                                            setData('start_time', value)
                                        }
                                    />
                                    <span>–</span>
                                    <TimeSelect
                                        name="end_time"
                                        value={data.end_time}
                                        options={timeOptions}
                                        onChange={(value: string) =>
                                            setData('end_time', value)
                                        }
                                    />
                                    <span className="text-sm text-muted-foreground">
                                        {calculateDuration(
                                            data.start_time,
                                            data.end_time,
                                        ) ?? ''}
                                    </span>
                                </div>
                                {submitted &&
                                    (!data.start_time || !data.end_time) && (
                                        <p className="text-xs text-red-500">
                                            Start and end time are required.
                                        </p>
                                    )}
                            </section>
                        </TabsContent>

                        <TabsContent value="multiple_days">
                            <section className="flex flex-col gap-2">
                                <Label>Select dates</Label>
                                <div className="flex gap-2">
                                    <DatePicker
                                        multiple
                                        value={multiValue}
                                        minDate={tomorrow}
                                        format="DD-MM-YYYY"
                                        onChange={handleMultiDateChange}
                                        render={(_value, openCalendar) => (
                                            <button
                                                type="button"
                                                className="w-fit cursor-pointer rounded border border-black px-3 py-1"
                                                onClick={openCalendar}
                                            >
                                                Select dates
                                            </button>
                                        )}
                                        mapDays={createMapDays(dutyDates)}
                                    />
                                </div>
                                {data.dates.length > 0 && (
                                    <p className="text-sm">
                                        {data.dates.join(', ')}
                                    </p>
                                )}
                                {submitted && data.dates.length === 0 && (
                                    <p className="text-xs text-red-500">
                                        At least one date is required.
                                    </p>
                                )}
                            </section>
                        </TabsContent>

                        <TabsContent value="range">
                            <section className="flex flex-col gap-2">
                                <Label>Select range</Label>
                                <div className="flex gap-2">
                                    <DatePicker
                                        range
                                        minDate={tomorrow}
                                        value={rangeValue}
                                        format="DD-MM-YYYY"
                                        onChange={handleRangeDateChange}
                                        render={(_value, openCalendar) => (
                                            <button
                                                type="button"
                                                className="w-fit cursor-pointer rounded border border-black px-3 py-1"
                                                onClick={openCalendar}
                                            >
                                                Select range
                                            </button>
                                        )}
                                        mapDays={createMapDays(dutyDates)}
                                    />
                                </div>
                                {data.dates.length > 0 && (
                                    <p className="text-sm">
                                        {data.dates.join(', ')}
                                    </p>
                                )}
                                {submitted && data.dates.length === 0 && (
                                    <p className="text-xs text-red-500">
                                        At least one date is required.
                                    </p>
                                )}
                            </section>
                        </TabsContent>
                    </Tabs>

                    <div>
                        <Textarea
                            placeholder="Optional note"
                            className="resize-none"
                            value={data.notes}
                            onChange={(
                                e: React.ChangeEvent<HTMLTextAreaElement>,
                            ) => setData('notes', e.target.value)}
                        ></Textarea>
                    </div>
                    <div>
                        <button
                            type="submit"
                            disabled={processing || auth.user.role === 'Guest'}
                            className="mr-5 rounded bg-mallard-green px-4 py-2 text-white disabled:opacity-50"
                        >
                            Submit request
                        </button>
                        <PrimaryLink href="/leaverequests" variant="outline">
                            Cancel
                        </PrimaryLink>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
