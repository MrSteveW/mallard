import { useForm, usePage } from '@inertiajs/react';
import { useState } from 'react';
import DatePicker from 'react-date-picker';
import 'react-date-picker/dist/DatePicker.css';
import DutyArchive from '@/components/DutyArchive';
import TimeSelect from '@/components/TimeSelect';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { getShiftBgColor } from '@/constants/shiftBgColors';
import { calculateDuration } from '@/lib/utils';
import type {
    DutyEvent,
    TimeOptions,
    ShiftTypeOption,
    CancelledOption,
    AssignableUser,
} from '@/types.ts';

interface DialogProps {
    initialEvent: DutyEvent | null;
    isDialogOpen: boolean;
    users: AssignableUser[];
    onClose: (open: boolean) => void;
    action: string;
    method?: 'post' | 'patch';
    onSuccess?: () => void;
}

export default function DutyCreateDialog({
    initialEvent,
    users,
    isDialogOpen,
    onClose,
    method,
    action,
    onSuccess,
}: DialogProps) {
    const { timeOptions } = usePage().props as unknown as {
        timeOptions: TimeOptions;
    };
    const { shiftTypeOptions } = usePage().props as unknown as {
        shiftTypeOptions: ShiftTypeOption[];
    };
    const { cancelledOptions } = usePage().props as unknown as {
        cancelledOptions: CancelledOption[];
    };

    const [submitted, setSubmitted] = useState(false);

    const { data, setData, post, patch, processing, reset } = useForm({
        id: initialEvent?.id ?? '',
        user_id: initialEvent?.user_id ?? '',
        user_name: initialEvent?.user_name ?? '',
        date:
            initialEvent?.start?.substring(0, 10) ??
            new Date().toISOString().substring(0, 10),
        notes: initialEvent?.notes ?? '',
        shift_type: initialEvent?.shift_type ?? '',
        start_time: initialEvent?.start_time ?? '',
        end_time: initialEvent?.end_time ?? '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setSubmitted(true);
        if (method === 'patch') {
            return patch(action, {
                onSuccess: () => {
                    reset();
                    setSubmitted(false);
                    onSuccess?.(); // refetch calendar events
                    onClose(false); // close dialog
                },
            });
        }
        post(action, {
            onSuccess: () => {
                reset();
                setSubmitted(false);
                onSuccess?.(); // refetch calendar events
                onClose(false); // close dialog
            },
        });
    };

    const handleShiftTypeChange = (value: string) => {
        const selected = shiftTypeOptions.find((opt) => opt.value === value);
        if (selected && value !== 'Off') {
            setData((prev) => ({
                ...prev,
                shift_type: value,
                start_time: selected.start_time ?? '',
                end_time: selected.end_time ?? '',
            }));
        } else {
            setData((prev) => ({
                ...prev,
                shift_type: value,
                start_time: '',
                end_time: '',
            }));
        }
    };

    const handleClose = (open: boolean) => {
        if (!open) {
            reset();
            setSubmitted(false);
        }
        onClose(open);
    };

    const bgColor = getShiftBgColor(data?.shift_type);

    return (
        <Dialog open={isDialogOpen} onOpenChange={handleClose}>
            <DialogContent className="sm:max-w-200">
                <form onSubmit={handleSubmit}>
                    <DialogHeader>
                        <DialogTitle className="text-center">
                            {initialEvent
                                ? `Duty: ${data.user_name}`
                                : 'New Duty'}
                        </DialogTitle>
                    </DialogHeader>

                    {/* If create - User */}
                    {method === 'post' && (
                        <div className="grid grid-cols-4 items-center py-2">
                            <Label htmlFor="date" className="text-xl">
                                User
                            </Label>
                            <div className="col-span-3">
                                <select
                                    value={data.user_id}
                                    onChange={(
                                        e: React.ChangeEvent<HTMLSelectElement>,
                                    ) => setData('user_id', e.target.value)}
                                    className="rounded-md border border-input bg-background p-0.5 text-sm ring-offset-background focus:ring-0"
                                >
                                    <option value="" disabled>
                                        Select user
                                    </option>
                                    {users.map((user) => (
                                        <option key={user.id} value={user.id}>
                                            {user.name}
                                        </option>
                                    ))}
                                </select>
                                {submitted && !data.user_id && (
                                    <p className="mt-1 text-xs text-red-500">
                                        User is required.
                                    </p>
                                )}
                            </div>
                        </div>
                    )}

                    {/* DATE */}
                    <div className="grid grid-cols-4 items-center py-2">
                        <Label htmlFor="date" className="text-xl">
                            Duty date
                        </Label>
                        <div className="col-span-3 [&_.react-calendar]:bg-white [&_.react-date-picker__wrapper]:bg-white">
                            <DatePicker
                                value={new Date(data.date + 'T00:00:00')}
                                onChange={(date) => {
                                    if (!date || Array.isArray(date)) return;
                                    const year = date.getFullYear();
                                    const month = String(
                                        date.getMonth() + 1,
                                    ).padStart(2, '0');
                                    const day = String(date.getDate()).padStart(
                                        2,
                                        '0',
                                    );
                                    setData('date', `${year}-${month}-${day}`);
                                }}
                                clearIcon={null}
                                openCalendarOnFocus={false}
                                format="dd/MM/yyyy"
                            />
                        </div>
                    </div>

                    {/* Shift type */}
                    <div className="grid grid-cols-4 items-center py-2">
                        <Label htmlFor="type" className="text-xl">
                            Shift type
                        </Label>
                        <div className="col-span-3">
                            <select
                                value={data.shift_type}
                                onChange={(
                                    e: React.ChangeEvent<HTMLSelectElement>,
                                ) => handleShiftTypeChange(e.target.value)}
                                className={`${bgColor} rounded-md border border-input bg-background p-0.5 text-sm ring-offset-background focus:ring-0`}
                            >
                                {shiftTypeOptions.map((opt) => (
                                    <option key={opt.value} value={opt.value}>
                                        {opt.label}
                                    </option>
                                ))}
                            </select>
                            {submitted && !data.shift_type && (
                                <p className="mt-1 text-xs text-red-500">
                                    Shift type is required.
                                </p>
                            )}
                        </div>
                    </div>

                    {/* Duty Hours */}
                    <div className="grid grid-cols-4 items-center py-2">
                        <Label htmlFor="time" className="text-xl">
                            Duty hours
                        </Label>
                        <div className="col-span-3 flex items-center gap-x-4">
                            <div className="flex items-center gap-x-2">
                                <div>
                                    <TimeSelect
                                        name="start_time"
                                        value={data.start_time}
                                        options={timeOptions}
                                        onChange={(value: string) =>
                                            setData('start_time', value)
                                        }
                                    />
                                </div>
                                <div>-</div>
                                <div>
                                    <TimeSelect
                                        name="end_time"
                                        value={data.end_time}
                                        options={timeOptions}
                                        onChange={(value: string) =>
                                            setData('end_time', value)
                                        }
                                    />
                                </div>
                            </div>
                            {submitted &&
                                (!data.start_time || !data.end_time) && (
                                    <p className="text-xs text-red-500">
                                        Start and end time are required.
                                    </p>
                                )}
                            <div className="ml-8 flex items-center text-sm whitespace-nowrap text-muted-foreground">
                                {calculateDuration(
                                    data.start_time,
                                    data.end_time,
                                ) ?? ''}
                            </div>
                        </div>
                    </div>

                    {/* NOTES */}
                    <div className="grid grid-cols-4 items-center gap-4 py-2">
                        <Label htmlFor="name" className="text-xl">
                            Notes
                        </Label>
                        <div className="col-span-3">
                            <Input
                                id="notes"
                                name="notes"
                                autoComplete="off"
                                value={data.notes}
                                onChange={(e) =>
                                    setData('notes', e.target.value)
                                }
                            />
                        </div>
                    </div>

                    <DialogFooter>
                        <div className="mr-6">
                            <DialogClose asChild>
                                <Button variant="outline">Close</Button>
                            </DialogClose>

                            <Button type="submit" disabled={processing}>
                                Save
                            </Button>
                        </div>
                        <div>
                            {method === 'patch' && (
                                <DutyArchive
                                    url={`/duties/${data.id}/cancel`}
                                    cancelledOptions={cancelledOptions}
                                    onSuccess={() => {
                                        reset();
                                        onSuccess?.();
                                        onClose(false);
                                    }}
                                />
                            )}
                        </div>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
