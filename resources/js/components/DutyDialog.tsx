import { useForm, usePage } from '@inertiajs/react';
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
import type {
    DutyEvent,
    TimeOptions,
    ShiftTypeOption,
    AbsenceOption,
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

export default function DutyDialog({
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
    const { absenceOptions } = usePage().props as unknown as {
        absenceOptions: AbsenceOption[];
    };

    const { data, setData, post, patch, processing, errors, reset } = useForm({
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
        if (method === 'patch') {
            return patch(action, {
                onSuccess: () => {
                    onSuccess?.(); // refetch calendar events
                    onClose(false); // close dialog
                },
            });
        }
        post(action, {
            onSuccess: () => {
                onSuccess?.(); // refetch calendar events
                onClose(false); // close dialog
            },
        });
    };

    const handleClose = (open: boolean) => {
        if (!open) reset();
        onClose(open);
    };

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
                                    className="text-sm"
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
                            </div>
                        </div>
                    )}

                    {/* DATE */}
                    <div className="grid grid-cols-4 items-center py-2">
                        <Label htmlFor="date" className="text-xl">
                            Duty date
                        </Label>
                        <div className="col-span-3">
                            <DatePicker
                                value={new Date(data.date)}
                                onChange={(date) => {
                                    if (!date || Array.isArray(date)) return;
                                    setData(
                                        'date',
                                        date.toISOString().substring(0, 10),
                                    );
                                }}
                                clearIcon={null}
                                openCalendarOnFocus={false}
                                format="dd/MM/yyyy"
                            />
                        </div>
                    </div>

                    {/* Shift type */}
                    <div className="grid grid-cols-4 items-center py-2">
                        <Label htmlFor="date" className="text-xl">
                            Shift type
                        </Label>
                        <div className="col-span-3">
                            <select
                                value={data.shift_type}
                                onChange={(
                                    e: React.ChangeEvent<HTMLSelectElement>,
                                ) => setData('shift_type', e.target.value)}
                                className="text-sm"
                            >
                                {shiftTypeOptions.map((opt) => (
                                    <option key={opt.value} value={opt.value}>
                                        {opt.label}
                                    </option>
                                ))}
                            </select>
                        </div>
                    </div>

                    <div className="grid grid-cols-4 items-center py-2">
                        <Label htmlFor="date" className="text-xl">
                            Duty date
                        </Label>
                        <div>
                            <div>
                                <TimeSelect
                                    name="start_time"
                                    value={data.start_time}
                                    options={timeOptions}
                                    onChange={(value: string) =>
                                        setData('start_time', value)
                                    }
                                />
                                {errors.start_time && (
                                    <p>{errors.start_time}</p>
                                )}
                            </div>
                            <div>
                                <TimeSelect
                                    name="end_time"
                                    value={data.end_time}
                                    options={timeOptions}
                                    onChange={(value: string) =>
                                        setData('end_time', value)
                                    }
                                />
                                {errors.end_time && <p>{errors.end_time}</p>}
                            </div>
                        </div>
                    </div>

                    {/* NAME */}
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
                        <DialogClose asChild>
                            <Button variant="outline">Close</Button>
                        </DialogClose>

                        <Button type="submit" disabled={processing}>
                            Save
                        </Button>

                        {method === 'patch' && (
                            <DutyArchive
                                url={`/duties/${data.id}`}
                                absenceOptions={absenceOptions}
                                onSuccess={() => {
                                    onSuccess?.();
                                    onClose(false);
                                }}
                            />
                        )}
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
