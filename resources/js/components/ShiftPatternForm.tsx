import { Form, Link, usePage } from '@inertiajs/react';
import { useState } from 'react';
import InputError from '@/components/auth/input-error';
import TimeSelect from '@/components/TimeSelect';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Label } from '@/components/ui/label';
import type { TimeOptions } from '@/types';

interface ShiftUser {
    id: number;
    name: string;
}

interface ShiftPatternProps {
    users: ShiftUser[];
    totalDays: number;
    action: string;
    method: 'post' | 'patch';
    initialData?: ShiftUser[];
}

export default function ShiftPatternForm({
    users,
    totalDays,
    action,
    method,
    initialData,
}: ShiftPatternProps) {
    const { timeOptions } = usePage().props as unknown as {
        timeOptions: TimeOptions;
    };
    const { errors } = usePage().props;

    const [selectedUser, setSelectedUser] = useState<number>();

    return (
        <Form action={action} method={method} className="flex flex-col gap-6">
            <div className="grid gap-6">
                <div className="flex w-full border bg-gray-50 p-5">
                    {/* User */}
                    <div className="flex w-30 flex-col gap-2">
                        <Label>Select user</Label>
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="outline">
                                    {users.find((g) => g.id === selectedUser)
                                        ?.name || 'Select User'}
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent>
                                <DropdownMenuRadioGroup
                                    value={selectedUser?.toString()}
                                    onValueChange={(v) =>
                                        setSelectedUser(parseInt(v, 10))
                                    }
                                >
                                    {users.map((user) => (
                                        <DropdownMenuRadioItem
                                            key={user.id}
                                            value={user.id.toString()}
                                        >
                                            {user.name}
                                        </DropdownMenuRadioItem>
                                    ))}
                                </DropdownMenuRadioGroup>
                            </DropdownMenuContent>
                        </DropdownMenu>
                        <input
                            type="hidden"
                            name="user_id"
                            value={selectedUser || ''}
                        />
                        <InputError message={errors.user_id} />
                    </div>
                </div>

                {/* Start Time */}
                <div className="flex flex-row gap-2">
                    <div>
                        <Label>Start time</Label>
                        <TimeSelect
                            name={'start_time'}
                            defaultValue={'00:00'}
                            options={timeOptions}
                        />
                    </div>
                    <div>
                        <Label>End time</Label>
                        <TimeSelect
                            name={'end_time'}
                            defaultValue={'00:00'}
                            options={timeOptions}
                        />
                    </div>
                </div>

                <input type="hidden" name={'day_number'} value={1} />

                <div className="m-4 flex flex-row items-center">
                    <Button
                        type="submit"
                        data-test="create-shiftpattern-button"
                    >
                        {method === 'post'
                            ? 'Add Shift pattern'
                            : 'Edit Shift pattern'}
                    </Button>

                    <Button variant="outline">
                        <Link href="/shiftpatterns">Cancel</Link>
                    </Button>
                </div>
            </div>
        </Form>
    );
}
