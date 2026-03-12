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
import type { AbsenceOption } from '@/types';

type ArchiveDutyProps = {
    url: string;
    absenceOptions: AbsenceOption[];
    onSuccess?: () => void;
};

export default function ArchiveDuty({
    url,
    absenceOptions,
    onSuccess,
}: ArchiveDutyProps) {
    const [absence, setAbsence] = useState<string>('');

    const handleDelete = () => {
        router.delete(url, {
            data: { absence },
            onSuccess: () => onSuccess?.(),
        });
    };

    const handleClose = (open: boolean) => {
        if (!open) setAbsence('');
    };

    return (
        <Dialog onOpenChange={handleClose}>
            <DialogTrigger asChild>
                <Button variant="destructive">Remove</Button>
            </DialogTrigger>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Confirm remove</DialogTitle>
                    <DialogDescription className="text-lg">
                        <div>Please select reason for absence</div>
                        <div className="align-center items-center justify-center justify-items-center">
                            <select
                                value={absence}
                                onChange={(
                                    e: React.ChangeEvent<HTMLSelectElement>,
                                ) => setAbsence(e.target.value)}
                            >
                                <option value="" disabled>
                                    Please select
                                </option>
                                {absenceOptions.map((opt) => (
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
                        disabled={!absence}
                        onClick={handleDelete}
                    >
                        Confirm remove
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
