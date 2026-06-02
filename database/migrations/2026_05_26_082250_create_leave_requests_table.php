<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('leave_reason');
            $table->jsonb('dates');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('declined_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
