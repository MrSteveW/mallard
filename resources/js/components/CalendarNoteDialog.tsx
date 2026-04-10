import { useForm } from '@inertiajs/react';
import { useEffect, useState } from 'react';
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
import type { CalendarNote } from '@/types';

interface DialogProps {
    initialNotes: { date: string; notes: CalendarNote[] };
    isDialogOpen: boolean;
    onClose: (open: boolean) => void;
    onSuccess?: () => void;
}

export default function DutyCreateDialog({
    initialNotes,
    isDialogOpen,
    onClose,
    onSuccess,
}: DialogProps) {
    const [editingNoteId, setEditingNoteId] = useState<number | null>(null);

    const formattedDate = (() => {
        const [year, month, day] = initialNotes.date.split('-').map(Number);
        if (!year || !month || !day) {
            return initialNotes.date;
        }

        return new Date(year, month - 1, day).toLocaleDateString('en-GB', {
            weekday: 'short',
            day: 'numeric',
            month: 'short',
        });
    })();

    const {
        data,
        setData,
        post,
        patch,
        delete: destroy,
        processing,
        reset,
    } = useForm({
        date: initialNotes?.date,
        note: '',
    });

    useEffect(() => {
        setData('date', initialNotes.date);
        setData('note', '');
    }, [initialNotes.date, isDialogOpen, setData]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editingNoteId) {
            return patch(`/calendar-notes/${editingNoteId}`, {
                onSuccess: () => {
                    reset();
                    setEditingNoteId(null);
                    onSuccess?.(); // refetch calendar events
                },
            });
        }

        post('/calendar-notes', {
            onSuccess: () => {
                reset();
                setEditingNoteId(null);
                onSuccess?.(); // refetch calendar events
            },
        });
    };

    const handleEdit = (note: CalendarNote) => {
        setEditingNoteId(note.id);
        setData('date', note.date);
        setData('note', note.note);
    };

    const handleDelete = (noteId: number) => {
        destroy(`/calendar-notes/${noteId}`, {
            onSuccess: () => {
                if (editingNoteId === noteId) {
                    setEditingNoteId(null);
                    setData('note', '');
                }
                onSuccess?.();
            },
        });
    };

    const handleCancelEdit = () => {
        setEditingNoteId(null);
        setData('note', '');
    };

    const handleClose = (open: boolean) => {
        if (!open) {
            reset();
            setEditingNoteId(null);
        }
        onClose(open);
    };

    return (
        <Dialog open={isDialogOpen} onOpenChange={handleClose}>
            <DialogContent className="sm:max-w-200">
                <form onSubmit={handleSubmit}>
                    <DialogHeader>
                        <DialogTitle className="text-center">
                            {formattedDate}
                        </DialogTitle>
                    </DialogHeader>

                    {initialNotes.notes.length > 0 && (
                        <div className="rounded-md border p-3 text-sm">
                            <ul className="space-y-1">
                                {initialNotes.notes.map((entry) => (
                                    <li
                                        key={entry.id}
                                        className="flex items-center justify-between gap-2"
                                    >
                                        <span>{entry.note}</span>
                                        <div className="flex items-center gap-2">
                                            <Button
                                                type="button"
                                                variant="outline"
                                                size="sm"
                                                onClick={() =>
                                                    handleEdit(entry)
                                                }
                                            >
                                                Edit
                                            </Button>
                                            <Button
                                                type="button"
                                                variant="destructive"
                                                size="sm"
                                                onClick={() =>
                                                    handleDelete(entry.id)
                                                }
                                                disabled={processing}
                                            >
                                                Delete
                                            </Button>
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    )}
                    <div className="grid grid-cols-4 items-center gap-4 py-2">
                        <Label htmlFor="name" className="text-xl">
                            New note
                        </Label>
                        <div className="col-span-3">
                            <Input
                                id="notes"
                                name="notes"
                                autoComplete="off"
                                value={data.note}
                                onChange={(e) =>
                                    setData('note', e.target.value)
                                }
                            />
                        </div>
                    </div>

                    <DialogFooter>
                        <div className="mr-6">
                            <DialogClose asChild>
                                <Button variant="outline">Close</Button>
                            </DialogClose>

                            {editingNoteId && (
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={handleCancelEdit}
                                >
                                    Cancel Edit
                                </Button>
                            )}

                            <Button type="submit" disabled={processing}>
                                {editingNoteId ? 'Update' : 'Save'}
                            </Button>
                        </div>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
