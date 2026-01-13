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
       Schema::create('fee_payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained()->cascadeOnDelete();
    $table->foreignId('fee_structure_id')
      ->constrained('fee_structures')
      ->cascadeOnDelete();
    $table->decimal('paid_amount', 10, 2);
    $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'online']);
    $table->string('transaction_id')->nullable()->unique();
    $table->enum('status', ['paid', 'pending', 'failed'])->default('paid');
    $table->date('payment_date');
    $table->text('remarks')->nullable();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
