<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('grade_id')->nullable();
            $table->date('date_started')->nullable();
            $table->date('date_ended')->nullable();
            $table->string('training')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
