<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /** @var array<string, mixed> */
    protected array $employeeData = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /** @param array<string, mixed> $data */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var User $user */
        $user = $this->record;

        $data['grade_id'] = $user->employee?->grade_id;
        $data['training'] = $user->employee?->training;

        return $data;
    }

    /** @param array<string, mixed> $data */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->employeeData = [
            'grade_id' => $data['grade_id'],
            'training' => $data['training'] ?? null,
        ];

        unset($data['grade_id'], $data['training']);

        return $data;
    }

    protected function afterSave(): void
    {
        /** @var User $user */
        $user = $this->record;

        $user->employee()->updateOrCreate(
            ['user_id' => $user->id],
            $this->employeeData,
        );
    }
}
