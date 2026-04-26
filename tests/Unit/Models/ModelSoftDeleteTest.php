<?php

use Illuminate\Database\Eloquent\SoftDeletes;

arch('no model uses soft deletes')
    ->expect('App\Models')
    ->not->toUseTrait(SoftDeletes::class);
