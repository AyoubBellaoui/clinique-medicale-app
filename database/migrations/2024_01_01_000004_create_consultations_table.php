<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();

            // Consultation date & time
            $table->dateTime('date_consultation')->useCurrent();

            // Medical files
            $table->string('diagnostic_file', 500)->nullable();
            $table->string('traitement_file', 500)->nullable();
            $table->string('ordonnance_file', 500)->nullable();
            $table->string('scanner_file', 500)->nullable();
            $table->string('analyse_file', 500)->nullable();

            // Relations
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('staff_id')
                ->constrained('staff_medicals')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
