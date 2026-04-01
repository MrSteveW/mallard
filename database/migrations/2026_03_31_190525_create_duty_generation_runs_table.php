<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('duty_generation_runs', function (Blueprint $table) {
            $table->id();
            $table->string('year_month', 7)->unique();
            $table->enum('triggered_by', ['schedule', 'manual']);
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duty_generation_runs');
    }
};
