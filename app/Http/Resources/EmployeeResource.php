<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
   * @mixin \App\Models\Employee
   */
class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'training' => $this->training,
            'grade_id' => $this->grade?->id,
            'grade_name' => $this->grade?->name,
        ];
    }
}
