<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Mail\UserCreated;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /** @var array<string, mixed> */
    protected array $employeeData = [];

    /** @param array<string, mixed> $data */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->employeeData = [
            'grade_id' => $data['grade_id'],
            'training' => $data['training'] ?? null,
        ];

        unset($data['grade_id'], $data['training']);

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var User $user */
        $user = $this->record;

        $user->employee()->create($this->employeeData);

        Mail::to($user)->queue(new UserCreated($user));
    }
}
