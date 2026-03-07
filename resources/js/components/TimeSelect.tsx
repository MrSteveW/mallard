interface Option {
    value: string;
    label: string;
}

interface Props {
    name: string;
    value?: string;
    defaultValue?: string;
    options: {
        hours: Option[];
        minutes: Option[];
    };
    onChange?: (value: string) => void;
}

export default function TimeSelect({
    name,
    value,
    defaultValue = '00:00',
    options,
    onChange,
}: Props) {
    const resolvedValue = value ?? defaultValue;
    const hour = resolvedValue.split(':')[0] ?? '00';
    const minute = resolvedValue.split(':')[1] ?? '00';

    const handleHourChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
        onChange?.(`${e.target.value}:${minute}`);
    };

    const handleMinuteChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
        onChange?.(`${hour}:${e.target.value}`);
    };

    return (
        <div className="flex items-center rounded-md border border-input bg-background ring-offset-background">
            <select
                className="border-none bg-transparent p-0 text-sm focus:ring-0"
                value={hour}
                onChange={handleHourChange}
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
                onChange={handleMinuteChange}
            >
                {options.minutes.map((opt) => (
                    <option key={opt.value} value={opt.value}>
                        {opt.label}
                    </option>
                ))}
            </select>

            <input type="hidden" name={name} value={`${hour}:${minute}`} />
        </div>
    );
}
