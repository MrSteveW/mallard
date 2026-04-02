import { Link } from '@inertiajs/react';
import { Head } from '@inertiajs/react';
import { ClipboardPen } from 'lucide-react';
import { PrimaryLink } from '@/components/ui/primary-link';
import AppLayout from '@/layouts/app-layout';

interface Task {
    id: number;
    name: string;
}

interface Props {
    tasks: Task[];
}

export default function Index({ tasks }: Props) {
    return (
        <AppLayout>
            <Head title="Tasks" />
            <div className="w-ful flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 md:w-1/2">
                <div className="my-3">
                    <PrimaryLink href="/tasks/create">+ Task</PrimaryLink>
                </div>
                {/* Display */}
                <div className="relative h-[calc(100vh-160px)] overflow-y-auto">
                    <div className="sticky top-0 z-10 grid grid-cols-[2fr_1fr] border-b bg-white pb-2 font-bold text-mallard-green">
                        <div>Name</div>
                        <div></div>
                    </div>
                    {tasks.map((task) => (
                        <div
                            key={task.id}
                            className="grid grid-cols-[2fr_1fr] items-center py-1.5 transition-colors hover:bg-slate-50"
                        >
                            <div className="text-lg">{task.name} </div>
                            <Link
                                href={`/tasks/${task.id}/edit`}
                                className="hover:text-mallard-green"
                            >
                                <ClipboardPen />
                            </Link>
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
