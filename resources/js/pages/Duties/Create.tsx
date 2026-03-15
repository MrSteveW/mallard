import { Head } from '@inertiajs/react';

import 'react-calendar/dist/Calendar.css';
import AssigningDuties from '@/components/AssigningDuties';
import AppLayout from '@/layouts/app-layout';
import type { AssignableUser, Task } from '@/types';

interface CreateDutyProps {
    users?: AssignableUser[];
    tasks?: Task[];
}

export default function Create({ users, tasks }: CreateDutyProps) {
    return (
        <AppLayout>
            <Head title="Duties" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className=""></div>
                <AssigningDuties
                    date="2026-03-12" //change to route param or query param
                    users={users}
                    tasks={tasks}
                />
            </div>
        </AppLayout>
    );
}
