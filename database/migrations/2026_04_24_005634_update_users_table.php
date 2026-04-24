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
        Schema::table('users', function (Blueprint $table) {

            // Remove default name column (not needed)
            $table->dropColumn('name');

            // Add role column
            $table->string('role')->default('medecin');

            // Add relation with staff_medical
            $table->foreignId('staff_id')
                ->nullable()
                ->constrained('staff_medical')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Remove foreign key first
            $table->dropForeign(['staff_id']);

            // Remove added columns
            $table->dropColumn(['role', 'staff_id']);

            // Restore name column if rollback
            $table->string('name');
        });
    }
};
