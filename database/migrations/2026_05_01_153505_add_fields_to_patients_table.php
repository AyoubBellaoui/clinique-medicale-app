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
        Schema::table('patients', function (Blueprint $table) {

            // Identité
            $table->string('email')->nullable();
            $table->string('groupe_sanguin')->nullable();

            // Médical
            $table->text('allergies')->nullable();
            $table->text('antecedents')->nullable();
            $table->foreignId('medecin_id')->nullable()->constrained('staff_medicals')->nullOnDelete();
            $table->string('statut_dossier')->default('actif');

            // Assurance
            $table->string('assurance_type')->nullable();
            $table->string('assurance_numero')->nullable();
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_tel')->nullable();
            $table->string('lien_urgence')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            //
        });
    }
};
