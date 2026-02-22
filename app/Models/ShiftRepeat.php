<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class ShiftRepeat extends Model
{
    public $timestamps = false;
 
    protected $fillable = [
        'total_days',
    ];

    protected $casts = [
        'start_on' => 'date',
    ];

}
