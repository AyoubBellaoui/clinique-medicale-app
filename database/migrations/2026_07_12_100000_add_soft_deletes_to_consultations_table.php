<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Consultation was the last clinical record still hard-deleted, and
     * ordonnances.consultation_id cascadeOnDelete()s from it — deleting a
     * consultation permanently destroyed its linked prescription and medication
     * lines. Soft-deleting stops the real DELETE (and its cascade) from firing,
     * same fix already applied to patients/staff_medicals.
     */
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
