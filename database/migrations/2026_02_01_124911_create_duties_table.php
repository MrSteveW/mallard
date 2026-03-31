<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('duties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('task_id')->nullable()->constrained('tasks');
            $table->date('date');
            $table->string('shift_type');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration');
            $table->string('notes')->nullable();
            $table->string('absence')->nullable();
            $table->string('status')->default('generated');
            $table->integer('generated_from_pattern_day')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duties');
    }
};