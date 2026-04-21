import { Link } from '@inertiajs/react';
import { BookmarkPlus } from 'lucide-react';
import type { CalendarNote } from '@/types';

interface HeaderProps {
    date: Date;
    calendarNotes: Record<string, CalendarNote[]>;
    editable?: boolean;
    linkable?: boolean;
    onAddOrEditNote?: (date: string) => void;
}

export default function DutyDayHeader({
    date,
    calendarNotes,
    editable,
    linkable,
    onAddOrEditNote,
}: HeaderProps) {
    const dateStr = [
        date.getFullYear(),
        String(date.getMonth() + 1).padStart(2, '0'),
        String(date.getDate()).padStart(2, '0'),
    ].join('-');

    const notes = calendarNotes[dateStr] ?? [];

    return (
        <>
            <div className="flex flex-col items-center justify-center">
                <div className="flex gap-x-2">
                    <div>
                        {date.toLocaleDateString('en-GB', {
                            weekday: 'short',
                            day: 'numeric',
                            month: 'short',
                        })}
                    </div>
                    <div>
                        {editable && onAddOrEditNote && (
                            <button
                                style={{ cursor: 'pointer' }}
                                type="button"
                                aria-label={`Add or edit note for ${dateStr}`}
                                onClick={() => onAddOrEditNote(dateStr)}
                            >
                                <BookmarkPlus className="size-4 text-mallard-green hover:text-mallard-orange" />
                            </button>
                        )}
                    </div>
                </div>
                {linkable && (
                    <Link
                        href={`/duties/${dateStr}/tasks`}
                        className="fc-day-label mr-2 text-mallard-green hover:text-mallard-orange"
                    >
                        Assign duties
                    </Link>
                )}
            </div>
            <div className="flex">
                {notes.length > 0 && (
                    <div className="flex flex-col text-[12px] leading-4 text-mallard-green">
                        {notes.slice(0, 2).map((entry, index) => (
                            <div key={`${dateStr}-${index}-${entry.note}`}>
                                {entry.note}
                            </div>
                        ))}
                        {notes.length > 2 && (
                            <div className="text-[11px] text-muted-foreground">
                                +{notes.length - 2} more
                            </div>
                        )}
                    </div>
                )}
            </div>
        </>
    );
}
