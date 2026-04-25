<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
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
        $data['grade_id'] = $this->record->employee?->grade_id;
        $data['training'] = $this->record->employee?->training;

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
        $this->record->employee()->updateOrCreate(
            ['user_id' => $this->record->id],
            $this->employeeData,
        );
    }
}
