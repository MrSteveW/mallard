import { Head } from '@inertiajs/react';
import ShiftPatternForm from '@/components/ShiftPatternForm';
import AppLayout from '@/layouts/app-layout';

interface User {
    id: number;
    name: string;
}

type CreateProps = {
    users: User[];
    totalDays: number;
};

export default function Create({ users, totalDays }: CreateProps) {
    return (
        <AppLayout>
            <Head title="Shift Patterns" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <ShiftPatternForm
                    users={users}
                    totalDays={totalDays}
                    action="/shiftpatterns"
                    method="post"
                />
            </div>
        </AppLayout>
    );
}
