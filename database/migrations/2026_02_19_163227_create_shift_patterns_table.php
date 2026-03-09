<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shift_patterns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->integer('day');
            $table->string('shift_type')->default('Off');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_patterns');
    }
};
