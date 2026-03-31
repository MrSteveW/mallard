<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shift_repeats', function (Blueprint $table) {
        $table->renameColumn('start_on', 'shift_pattern_start_date');
    });
    }

   
    public function down(): void
    {
        Schema::table('shift_repeats', function (Blueprint $table) {
        $table->renameColumn('shift_pattern_start_date', 'start_on');
    });
    }
};
