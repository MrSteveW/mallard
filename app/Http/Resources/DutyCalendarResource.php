<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DutyCalendarResource extends JsonResource
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
            'title' => $this->user->name,
            'start' => $this->date.'T'.$this->start_time.':00',
            'end' => $this->end_time < $this->start_time
                ? $this->date.'T23:59:00'
                : $this->date.'T'.$this->end_time.':00',
            'extendedProps' => [
                'user_id' => $this->user_id,
                'shift_type' => $this->shift_type,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'notes' => $this->notes,
                'grade' => $this->user->employee?->grade?->name ?? '',
                'cancelled_at' => $this->cancelled_at,
                'cancel_reason' => $this->cancel_reason,
                'sort_order' => $this->cancelled_at ? 1 : 0,
            ],
        ];
    }
}
