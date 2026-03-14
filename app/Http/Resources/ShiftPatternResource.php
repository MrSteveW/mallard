<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ShiftPatternResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'day' => $this->day,
            'shift_type' => $this->shift_type,
            'start_time' => Carbon::createFromFormat('H:i:s', $this->start_time)->format('H:i'),
            'end_time'   => Carbon::createFromFormat('H:i:s', $this->end_time)->format('H:i'),
        ];
    }
}
