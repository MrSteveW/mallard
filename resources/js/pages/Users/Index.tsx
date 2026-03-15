import { Link } from '@inertiajs/react';
import { Head } from '@inertiajs/react';
import { UserRoundPen } from 'lucide-react';
import { PrimaryLink } from '@/components/ui/primary-link';
import AppLayout from '@/layouts/app-layout';
import type { User } from '@/types';

interface Props {
    users: User[];
    totalCount: number;
}

export default function Index({ users, totalCount }: Props) {
    return (
        <AppLayout>
            <Head title="Users" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="my-3 flex flex-row">
                    <div>
                        <PrimaryLink href="/users/create">+ User</PrimaryLink>
                    </div>
                    <div className="ml-6">Total users: {totalCount}</div>
                </div>
                {/* Display */}
                <div className="relative h-[calc(100vh-160px)] w-1/2 overflow-y-auto">
                    <div className="sticky top-0 z-10 grid grid-cols-[2fr_2fr_2fr_1fr] border-b bg-white pb-2 font-bold text-mallard-green">
                        <div>Name</div>
                        <div>Grade</div>
                        <div>Role</div>
                        <div></div>
                    </div>
                    {users.map((user) => (
                        <div
                            key={user.id}
                            className="grid grid-cols-[2fr_2fr_2fr_1fr] items-center py-1.5 transition-colors hover:bg-slate-100"
                        >
                            <div className="text-lg">{user.name}</div>
                            <div className="text-lg">
                                {user.employee?.grade_name}
                            </div>
                            <div className="text-lg">{user.role}</div>
                            <Link
                                href={`/users/${user.id}/edit`}
                                className="hover:text-mallard-green"
                            >
                                <UserRoundPen />
                            </Link>
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
