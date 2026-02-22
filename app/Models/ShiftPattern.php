<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ShiftPattern extends Model
{
      protected $fillable = [
        'user_id',
        'day_number',
        'status',
        'start_time',
        'end_time',
    ];

    public $timestamps = false;

    use SoftDeletes;

    protected function casts(): array {
    return [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];
}

 public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
