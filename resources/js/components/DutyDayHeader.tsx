import { BookmarkPlus } from 'lucide-react';
import type { CalendarNote } from '@/types';

interface HeaderProps {
    date: Date;
    calendarNotes: Record<string, CalendarNote[]>;
    editable?: boolean;
    onAddOrEditNote?: (date: string) => void;
}

export default function DutyDayHeader({
    date,
    calendarNotes,
    editable,
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
            <div className="flex items-center justify-center">
                <div className="fc-day-label mr-2">
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
                            <BookmarkPlus className="size-4" />
                        </button>
                    )}
                </div>
            </div>
            <div className="flex">
                {notes.length > 0 && (
                    <div className="flex flex-col text-[12px] leading-4">
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
