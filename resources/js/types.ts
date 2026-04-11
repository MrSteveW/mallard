export interface User {
    id: number;
    name: string;
    email: string;
    role: string | null;
    avatar?: string;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    created_at: string;
    updated_at: string;
    employee?: Employee | null;
}

export interface Employee {
    grade_id: number | null;
    grade_name: string | null;
    training: string | null;
}

export interface Grade {
    id: number;
    name: string;
}

export interface Duty {
    id: number;
    user_id: number;
    task_id: number | null;
    dutydate: string;
    shift_type?: string;
    hours?: number;
}

export interface Task {
    id: number;
    name: string;
}

export interface Grade {
    id: number;
    name: string;
}

export interface AssignableUser {
    id: number;
    name: string;
    grade: string;
}

export interface TimeOptions {
    hours: { value: string; label: string }[];
    minutes: { value: string; label: string }[];
}

export interface ShiftPatternUser {
    id: number;
    name: string;
}

export interface ShiftTypeOption {
    value: string;
    label: string;
    start_time: string | null;
    end_time: string | null;
}

export interface CancelledOption {
    value: string;
}

export interface ShiftPatternDay {
    user_id: number | '';
    day: number;
    shift_type: string;
    start_time: string;
    end_time: string;
}

export interface DutyEvent {
    id: string;
    user_id: number;
    user_name: string;
    start: string;
    end: string;
    shift_type: string;
    start_time: string;
    end_time: string;
    grade: string;
    notes: string;
    cancelled_at: string | null;
    cancel_reason: string | null;
}

export interface CalendarNote {
    id: number;
    date: string;
    note: string;
    source: string;
}
