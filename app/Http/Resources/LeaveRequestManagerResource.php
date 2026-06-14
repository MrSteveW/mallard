<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\LeaveRequest
 */
class LeaveRequestManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user->name,
            'dates' => $this->dates,
            'start_time' => $this->start_time ? substr($this->start_time, 0, 5) : null,
            'end_time' => $this->end_time ? substr($this->end_time, 0, 5) : null,
            'leave_reason' => $this->leave_reason,
            'status' => match (true) {
                (bool) $this->approved_by => 'Approved',
                (bool) $this->declined_by => 'Declined',
                default => 'Pending',
            },
            'manager_name' => match (true) {
                (bool) $this->approved_by => $this->approvedBy->name,
                (bool) $this->declined_by => $this->declinedBy->name,
                default => '',
            },
        ];
    }
}
