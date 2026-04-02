import type { InertiaLinkProps } from '@inertiajs/react';
import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(url: NonNullable<InertiaLinkProps['href']>): string {
    return typeof url === 'string' ? url : url.url;
}

export function calculateDuration(
    startTime: string,
    endTime: string,
): string | null {
    if (!startTime || !endTime) return null;

    const [startHour, startMin] = startTime.split(':').map(Number);
    const [endHour, endMin] = endTime.split(':').map(Number);

    const startMinutes = startHour * 60 + startMin;
    let endMinutes = endHour * 60 + endMin;

    if (endMinutes <= startMinutes) {
        endMinutes += 24 * 60; // crosses midnight
    }

    const diff = endMinutes - startMinutes;
    if (diff === 0) return null;

    const hours = Math.floor(diff / 60);
    const minutes = diff % 60;

    return minutes === 0 ? `${hours}h` : `${hours}h ${minutes}m`;
}
