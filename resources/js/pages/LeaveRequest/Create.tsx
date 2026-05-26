import { Head } from '@inertiajs/react';
import React from 'react';
import AppLayout from '@/layouts/app-layout';

export default function Create() {
    return (
        <AppLayout>
            <Head title="Shift patterns" />
            <div className="relative my-3 h-[calc(100vh-100px)] w-full overflow-auto rounded-lg border bg-slate-50">
                <div className="grid">
                    {/* --- STICKY HEADER --- */}
                    <div className="sticky top-0 z-20 flex items-center justify-center border-r border-b bg-white"></div>
                    <div className="sticky top-0 z-20 flex items-center justify-center border-r border-b bg-white"></div>

                    <div>Hi Hi</div>
                </div>
            </div>
        </AppLayout>
    );
}
