import { useState } from 'react';

interface Option {
    value: string;
    label: string;
}

interface Props {
    name: string;
    defaultValue?: string | number;
    options: {
        hours: Option[];
        minutes: Option[];
    };
}

export default function TimeSelect({
    name,
    defaultValue = '00:00',
    options,
}: Props) {
    const safeDefault =
        typeof defaultValue === 'number'
            ? `${String(defaultValue).padStart(2, '0')}:00`
            : defaultValue;

    const [hour, setHour] = useState(safeDefault.split(':')[0] || '00');
    const [minute, setMinute] = useState(safeDefault.split(':')[1] || '00');

    const timeValue = `${hour}:${minute}:00`;

    return (
        <div className="flex items-center gap-1 rounded-md border border-input bg-background px-3 py-2 ring-offset-background">
            <select
                className="border-none bg-transparent p-0 text-sm focus:ring-0"
                value={hour}
                onChange={(e) => setHour(e.target.value)}
            >
                {options.hours.map((opt) => (
                    <option key={opt.value} value={opt.value}>
                        {opt.label}
                    </option>
                ))}
            </select>

            <span className="text-muted-foreground">:</span>

            <select
                className="border-none bg-transparent p-0 text-sm focus:ring-0"
                value={minute}
                onChange={(e) => setMinute(e.target.value)}
            >
                {options.minutes.map((opt) => (
                    <option key={opt.value} value={opt.value}>
                        {opt.label}
                    </option>
                ))}
            </select>

            <input type="hidden" name={name} value={timeValue} />
        </div>
    );
}
