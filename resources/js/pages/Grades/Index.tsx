import { Link } from '@inertiajs/react';
import { Head } from '@inertiajs/react';
import { SquarePen } from 'lucide-react';
import { PrimaryLink } from '@/components/ui/primary-link';
import AppLayout from '@/layouts/app-layout';
import type { Grade } from '@/types';

interface IndexProps {
    grades: Grade[];
}

export default function Index({ grades }: IndexProps) {
    return (
        <AppLayout>
            <Head title="Grades" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="my-3">
                    <PrimaryLink href="/grades/create">+ Grade</PrimaryLink>
                </div>
                {/* Display */}
                <div className="relative h-[calc(100vh-160px)] w-1/2 overflow-y-auto">
                    <div className="sticky top-0 z-10 grid grid-cols-[2fr_1fr] border-b bg-white pb-2 font-bold text-mallard-green">
                        <div>Name</div>
                        <div></div>
                    </div>
                    {grades.map((grade) => (
                        <div
                            key={grade.id}
                            className="grid grid-cols-[2fr_1fr] items-center py-1.5 transition-colors hover:bg-slate-50"
                        >
                            <div className="text-lg">{grade.name} </div>
                            <Link
                                href={`/grades/${grade.id}/edit`}
                                className="hover:text-slate-300 hover:underline"
                            >
                                <SquarePen />
                            </Link>
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
