<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    /** @use HasFactory<\Database\Factories\LeaveRequestFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'dates' => 'array',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /** @param Builder<LeaveRequest> $query */
    public function scopeWhereTodayOrAfter(Builder $query): Builder
    {
        $driver = config('database.connections.'.config('database.default').'.driver');

        if ($driver === 'pgsql') {
            return $query->whereRaw('EXISTS (SELECT 1 FROM jsonb_array_elements_text(dates) AS d WHERE d::date >= CURRENT_DATE)');
        }

        return $query->whereRaw("EXISTS (SELECT 1 FROM json_each(dates) WHERE json_each.value >= date('now'))");
    }
}
