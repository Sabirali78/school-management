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
       Schema::create('timetables', function (Blueprint $table) {
    $table->id();
    $table->foreignId('class_id')->constrained()->cascadeOnDelete();
    $table->foreignId('section_id')->constrained()->cascadeOnDelete();
    $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
    $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
    $table->enum('day', ['monday','tuesday','wednesday','thursday','friday','saturday']);
    $table->time('start_time');
    $table->time('end_time');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
