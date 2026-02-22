<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_repeats', function (Blueprint $table) {
            $table->id();
            $table->integer('total_days');
            $table->date('start_on');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('shift_repeats');
    }
};
