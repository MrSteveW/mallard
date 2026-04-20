<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
   * @mixin \App\Models\Duty
   */
class DutyCalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $startTime = substr($this->start_time, 0, 5);
        $endTime = substr($this->end_time, 0, 5);

        return [
            'id' => $this->id,
            'title' => $this->user->name,
            'start' => $this->date.'T'.$startTime.':00',
            'end' => $endTime < $startTime
                ? $this->date.'T23:59:00'
                : $this->date.'T'.$endTime.':00',
            'extendedProps' => [
                'user_id' => $this->user_id,
                'shift_type' => $this->shift_type,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'notes' => $this->notes,
                'grade' => $this->user->employee?->grade?->name ?? '',
                'cancelled_at' => $this->cancelled_at,
                'cancel_reason' => $this->cancel_reason,
                'sort_order' => $this->cancelled_at ? 1 : 0,
            ],
        ];
    }
}
