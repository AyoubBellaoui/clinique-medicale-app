<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff_medicals', function (Blueprint $table) {
            $table->id();

            // =========================
            // Identity
            // =========================
            $table->string('nom');
            $table->string('prenom');
            $table->string('cin')->unique();
            $table->string('gender')->nullable();
            $table->date('date_naissance')->nullable();

            // =========================
            // Contact
            // =========================
            $table->string('email')->unique()->nullable();
            $table->string('telephone')->nullable();
            $table->text('adresse')->nullable();

            // =========================
            // Professional
            // =========================
            $table->string('specialite')->nullable();
            $table->string('degree')->nullable();
            $table->string('school')->nullable();
            $table->year('grad_year')->nullable();
            $table->string('languages')->nullable();

            // =========================
            // Contract
            // =========================
            $table->date('date_embauche')->nullable();
            $table->decimal('salaire', 10, 2)->nullable();
            $table->string('contract_type')->nullable();
            $table->string('schedule')->nullable();
            $table->string('status')->default('actif');

            // =========================
            // System
            // =========================
            $table->enum('role', [
                'medecin',
                'infirmier',
                'admin',
                'technicien',
                'secretaire'
            ])->default('medecin');

            // =========================
            // Notes
            // =========================
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_medicals');
    }
};
