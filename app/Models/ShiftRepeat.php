<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class ShiftRepeat extends Model
{
    public $timestamps = false;
 
     protected $guarded = [];

    protected $casts = [
        'shift_pattern_start_date' => 'date',
    ];

}
