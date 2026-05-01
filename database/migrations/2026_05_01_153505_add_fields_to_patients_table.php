<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            // Identité
            $table->string('email')->unique()->nullable();
            $table->string('groupe_sanguin')->nullable();

            // Médical
            $table->text('allergies')->nullable();
            $table->text('antecedents')->nullable();

            // Relation médecin
            $table->foreignId('medecin_id')
                ->nullable()
                ->constrained('staff_medicals')
                ->nullOnDelete();

            $table->index('medecin_id'); // 🔥 performance

            $table->string('statut_dossier')->default('actif');

            // Assurance
            $table->string('assurance_type')->nullable();
            $table->string('assurance_numero')->nullable();

            // Contact urgence
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_tel')->nullable();
            $table->string('lien_urgence')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            $table->dropForeign(['medecin_id']);
            $table->dropIndex(['medecin_id']); // 🔥 important

            $table->dropColumn([
                'email',
                'groupe_sanguin',
                'allergies',
                'antecedents',
                'medecin_id',
                'statut_dossier',
                'assurance_type',
                'assurance_numero',
                'contact_urgence_nom',
                'contact_urgence_tel',
                'lien_urgence',
            ]);
        });
    }
};
