import type { EventContentArg } from '@fullcalendar/core';
import type { DutyEvent } from '@/types';

export function mapToDutyEvent(arg: EventContentArg): DutyEvent {
    return {
        id: arg.event.id,
        user_id: arg.event.extendedProps.user_id,
        user_name: arg.event.title,
        start: arg.event.startStr,
        end: arg.event.endStr,
        shift_type: arg.event.extendedProps.shift_type,
        start_time: arg.event.extendedProps.start_time,
        end_time: arg.event.extendedProps.end_time,
        grade: arg.event.extendedProps.grade,
        notes: arg.event.extendedProps.notes,
    };
}
