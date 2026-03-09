export const SHIFT_TYPE_COLORS: Record<string, string> = {
    Off: 'bg-white',
    Early: 'bg-amber-200',
    Late: 'bg-green-300',
    Night: 'bg-blue-200',
};

export const getShiftBgColor = (shiftType?: string, day?: number): string => {
    if (
        day !== undefined &&
        shiftType === 'Off' &&
        (day % 7 === 6 || day % 7 === 0)
    )
        return 'bg-gray-200';
    return SHIFT_TYPE_COLORS[shiftType ?? ''] ?? 'bg-white';
};
