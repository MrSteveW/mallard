<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'grade_id',
        'date_started',
        'date_ended',
        'training',
    ];

    public $timestamps = false;

    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'date_started' => 'datetime',
            'date_ended' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }
}
