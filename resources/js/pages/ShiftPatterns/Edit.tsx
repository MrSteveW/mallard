import { Head } from '@inertiajs/react';
import ShiftPatternForm from '@/components/ShiftPatternForm';
import AppLayout from '@/layouts/app-layout';
import type { ShiftPatternUser, ShiftPatternDay } from '@/types';

type EditProps = {
    user: ShiftPatternUser;
    initialPattern: ShiftPatternDay[];
};

export default function Edit({ user, initialPattern }: EditProps) {
    return (
        <AppLayout>
            <Head title="Shift Patterns" />
            <div className="h-full overflow-x-auto">
                <ShiftPatternForm
                    user={user}
                    initialPattern={initialPattern}
                    action={`/shiftpatterns/${user.id}`}
                    method="patch"
                />
            </div>
        </AppLayout>
    );
}
