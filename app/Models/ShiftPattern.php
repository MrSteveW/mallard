<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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

    use SoftDeletes;

    protected function casts(): array {
    return [

    ];
}

// $CarbonTime = Carbon::createFromFormat('H:i', $shiftPattern->start_time);

 public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
