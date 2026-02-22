import { Form, Link, usePage } from '@inertiajs/react';
import { useState } from 'react';
import InputError from '@/components/auth/input-error';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { User, Employee, Grade } from '@/types';

type UserFormProps = {
    roles: string[];
    grades: Grade[];
    action: string;
    method: 'post' | 'patch';
    initialData?: User;
};

export default function UserForm({
    roles,
    grades,
    action,
    method,
    initialData,
}: UserFormProps) {
    const { errors } = usePage().props;

    const [selectedRole, setSelectedRole] = useState(initialData?.role ?? '');
    const [selectedGrade, setSelectedGrade] = useState<number>(
        initialData?.employee?.grade_id ?? 0,
    );

    return (
        <Form
            action={action}
            method={method}
            resetOnSuccess={['password']}
            className="flex flex-col gap-6"
        >
            <div className="grid gap-6">
                <div className="flex w-full border bg-gray-50 p-5">
                    <div className="mx-4 grid w-1/4 gap-2">
                        <Label htmlFor="name">Name</Label>
                        <Input
                            id="name"
                            type="text"
                            name="name"
                            required
                            autoFocus
                            autoComplete="off"
                            defaultValue={initialData?.name}
                        />
                        <InputError message={errors.name} />
                    </div>

                    <div className="mx-4 grid w-1/4 gap-2">
                        <Label htmlFor="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            required
                            autoComplete="new-email"
                            defaultValue={initialData?.email}
                        />
                        <InputError message={errors.email} />
                    </div>

                    <div className="flex flex-col gap-2">
                        <Label>Role</Label>
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="outline">
                                    {selectedRole || 'Select Role'}
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent>
                                <DropdownMenuRadioGroup
                                    value={selectedRole}
                                    onValueChange={setSelectedRole}
                                >
                                    {roles.map((role) => (
                                        <DropdownMenuRadioItem
                                            key={role}
                                            value={role}
                                        >
                                            {role}
                                        </DropdownMenuRadioItem>
                                    ))}
                                </DropdownMenuRadioGroup>
                            </DropdownMenuContent>
                        </DropdownMenu>
                        <input type="hidden" name="role" value={selectedRole} />
                        <InputError message={errors.role} />
                    </div>
                </div>

                {/* Grade */}
                <div className="flex w-30 flex-col gap-2">
                    <Label>Grade</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                            <Button variant="outline">
                                {grades.find((g) => g.id === selectedGrade)
                                    ?.name || 'Select Grade'}
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent>
                            <DropdownMenuRadioGroup
                                value={selectedGrade.toString()}
                                onValueChange={(v) =>
                                    setSelectedGrade(parseInt(v, 10))
                                }
                            >
                                {grades.map((grade) => (
                                    <DropdownMenuRadioItem
                                        key={grade.id}
                                        value={grade.id.toString()}
                                    >
                                        {grade.name}
                                    </DropdownMenuRadioItem>
                                ))}
                            </DropdownMenuRadioGroup>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <input
                        type="hidden"
                        name="grade_id"
                        value={selectedGrade || ''}
                    />
                    <InputError message={errors.grade_id} />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="training">Training</Label>
                    <Input
                        id="training"
                        type="text"
                        name="training"
                        autoComplete="off"
                        defaultValue={
                            (initialData?.employee as Employee)?.training ?? ''
                        }
                    />
                    <InputError message={errors.training} />
                </div>

                {method === 'post' ? (
                    <div className="grid gap-2">
                        <div className="flex items-center">
                            <Label htmlFor="password">Password</Label>
                        </div>
                        <Input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autoComplete="new-password"
                        />
                        <InputError message={errors.password} />
                    </div>
                ) : (
                    <></>
                )}

                <div className="m-4 flex flex-row items-center">
                    <Button type="submit" data-test="create-user-button">
                        {method === 'post' ? 'Add user' : 'Edit user'}
                    </Button>

                    <Button variant="outline">
                        <Link href="/users">Cancel</Link>
                    </Button>
                </div>
            </div>
        </Form>
    );
}
