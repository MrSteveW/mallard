import { Head } from '@inertiajs/react';
import ShiftPatternForm from '@/components/ShiftPatternForm';
import AppLayout from '@/layouts/app-layout';
import type { ShiftPatternUser } from '@/types';

type CreateProps = {
    userOptions: ShiftPatternUser[];
    totalDays: number;
};

export default function Create({ userOptions, totalDays }: CreateProps) {
    return (
        <AppLayout>
            <Head title="Shift Patterns" />
            <div className="h-full overflow-x-auto">
                <ShiftPatternForm
                    userOptions={userOptions}
                    totalDays={totalDays}
                    action="/shiftpatterns"
                    method="post"
                />
            </div>
        </AppLayout>
    );
}
