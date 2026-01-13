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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'phone')) {
                $table->string('phone')->nullable()->after('roll_number');
            }
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            // Add timestamps if they don't exist
            if (!Schema::hasColumn('students', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // drop columns if exist
            $drop = [];
            if (Schema::hasColumn('students', 'phone')) {
                $drop[] = 'phone';
            }
            if (Schema::hasColumn('students', 'address')) {
                $drop[] = 'address';
            }
            if (Schema::hasColumn('students', 'created_at')) {
                $drop[] = 'created_at';
            }
            if (Schema::hasColumn('students', 'updated_at')) {
                $drop[] = 'updated_at';
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
