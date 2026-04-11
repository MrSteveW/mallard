import { router } from '@inertiajs/react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import type { CancelledOption } from '@/types';

type DutyArchiveProps = {
    url: string;
    cancelledOptions: CancelledOption[];
    onSuccess?: () => void;
};

export default function DutyArchive({
    url,
    cancelledOptions,
    onSuccess,
}: DutyArchiveProps) {
    const [cancelReason, setCancelReason] = useState<string>('');

    const handleCancel = () => {
        router.patch(
            url,
            { cancel_reason: cancelReason },
            { onSuccess: () => onSuccess?.() },
        );
    };

    const handleClose = (open: boolean) => {
        if (!open) setCancelReason('');
    };

    return (
        <Dialog onOpenChange={handleClose}>
            <DialogTrigger asChild>
                <Button variant="destructive">Cancel Duty</Button>
            </DialogTrigger>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Confirm cancel</DialogTitle>
                    <DialogDescription className="text-lg">
                        <div>Please select reason for absence</div>
                        <div className="align-center items-center justify-center justify-items-center">
                            <select
                                value={cancelReason}
                                onChange={(
                                    e: React.ChangeEvent<HTMLSelectElement>,
                                ) => setCancelReason(e.target.value)}
                            >
                                <option value="" disabled>
                                    Please select
                                </option>
                                {cancelledOptions.map((opt) => (
                                    <option key={opt.value} value={opt.value}>
                                        {opt.value}
                                    </option>
                                ))}
                            </select>
                        </div>
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <DialogClose asChild>
                        <Button variant="outline">Cancel</Button>
                    </DialogClose>
                    <Button
                        variant="destructive"
                        disabled={!cancelReason}
                        onClick={handleCancel}
                    >
                        Confirm cancel
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
