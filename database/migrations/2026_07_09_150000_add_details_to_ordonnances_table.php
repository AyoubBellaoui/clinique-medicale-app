<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ordonnances', function (Blueprint $table) {
            $table->foreignId('patient_id')
                ->after('consultation_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('staff_id')
                ->after('patient_id')
                ->constrained('staff_medicals')
                ->cascadeOnDelete();

            $table->date('date_prescription')->after('staff_id');
            $table->string('duree_validite')->nullable()->after('date_prescription');
            $table->string('diagnostic_associe')->nullable()->after('duree_validite');
            $table->text('instructions')->nullable()->after('diagnostic_associe');
            $table->unsignedTinyInteger('renouvelable')->default(0)->after('instructions');
            $table->boolean('substitution_autorisee')->default(true)->after('renouvelable');
            $table->text('notes_privees')->nullable()->after('substitution_autorisee');
        });

        DB::statement('ALTER TABLE ordonnances MODIFY consultation_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE ordonnances MODIFY contenu TEXT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordonnances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('patient_id');
            $table->dropConstrainedForeignId('staff_id');
            $table->dropColumn([
                'date_prescription',
                'duree_validite',
                'diagnostic_associe',
                'instructions',
                'renouvelable',
                'substitution_autorisee',
                'notes_privees',
            ]);
        });
    }
};
