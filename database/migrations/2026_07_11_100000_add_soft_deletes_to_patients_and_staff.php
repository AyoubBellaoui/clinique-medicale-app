<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Consultations, rendez-vous, file d'attente, ordonnances, and factures all
     * cascadeOnDelete() from patients/staff_medicals. A hard delete of a patient
     * or staff member therefore permanently destroys their entire medical and
     * billing history with a single click and no way back. Soft-deleting means
     * Eloquent's delete() no longer issues a real DELETE, so those cascades never
     * fire — the record is archived (hidden from normal queries), not destroyed.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('staff_medicals', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('staff_medicals', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
