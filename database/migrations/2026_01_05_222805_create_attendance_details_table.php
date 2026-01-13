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
        Schema::create('attendance_details', function (Blueprint $table) {
    $table->foreignId('attendance_id')->constrained()->cascadeOnDelete();
    $table->foreignId('student_id')->constrained();
    $table->enum('status', ['present','absent','late']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_details');
    }
};
