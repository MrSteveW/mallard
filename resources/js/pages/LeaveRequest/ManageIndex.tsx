import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { useState } from 'react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import LeaveRequestTable from '@/components/LeaveRequestTable';
import type { ManagerLeaveRequest } from '@/types.ts';



interface ManageIndexProps {
    leaveRequests: ManagerLeaveRequest[];
}

export default function ManageIndex({ leaveRequests }: ManageIndexProps) {
    const [viewStatus, setViewStatus] = useState('pending');
    return (
        <AppLayout>
            <Head title="Manage Leave Requests" />
        <section className="py-5">
         <Tabs
                        value={viewStatus}
                        onValueChange={setViewStatus}
                    ><div className="flex items-center justify-center">
                        <TabsList className="group-data-[orientation=horizontal]/tabs:h-12">

                            <TabsTrigger className="px-5 text-lg" value="pending">
                                <div className="flex items-center gap-1">
                                <div>Pending</div> <div className="text-sm text-mallard-orange">({leaveRequests.filter((LR) => LR.status === 'Pending').length})</div>
                                </div>
                            </TabsTrigger>
                            <TabsTrigger className="px-6 text-lg" value="approved">
                                Approved
                            </TabsTrigger>
                            <TabsTrigger className="px-6 text-lg" value="declined">
                                Declined
                            </TabsTrigger>
                        </TabsList>
                        </div>

                        <TabsContent value="pending">
                            <section className="flex flex-col gap-3 mt-2">
                                <div className="text-xl flex justify-center">Pending Leave Requests</div>
                                <LeaveRequestTable leaveRequests={leaveRequests.filter((LR) => LR.status === 'Pending')} />
                                
                                
                            </section>
                        </TabsContent>

                        <TabsContent value="approved">
                            <section className="flex flex-col gap-3 mt-2">
                                <div className="text-xl flex justify-center">Approved Leave Requests</div>
                                <LeaveRequestTable leaveRequests={leaveRequests.filter((LR) => LR.status === 'Approved')} />
                            </section>
                        </TabsContent>

                        <TabsContent value="declined">
                            <section className="flex flex-col gap-3 mt-2">
                                <div className="text-xl flex justify-center">Declined Leave Requests</div>
                                <LeaveRequestTable leaveRequests={leaveRequests.filter((LR) => LR.status === 'Declined')} />

                            </section>
                        </TabsContent>
                    </Tabs>
                    </section>
        </AppLayout>
    );
}
