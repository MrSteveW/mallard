<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $start_time
 * @property string $end_time
 */
class ShiftPattern extends Model
{
    protected $fillable = [
        'user_id',
        'day',
        'shift_type',
        'start_time',
        'end_time',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [

        ];
    }

    // $CarbonTime = Carbon::createFromFormat('H:i', $shiftPattern->start_time);

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function startTime(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? substr($value, 0, 5) : null,
        );
    }

    protected function endTime(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? substr($value, 0, 5) : null,
        );
    }
}
