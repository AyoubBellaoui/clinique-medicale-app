<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();

            $table->date('date_consultation')->nullable();

            $table->string('diagnostic_path', 500)->nullable();
            $table->string('traitement_path', 500)->nullable();
            $table->string('ordonnance_path', 500)->nullable();
            $table->string('scanner_path', 500)->nullable();
            $table->string('analyse_path', 500)->nullable();

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
        Schema::dropIfExists('CONSULTATION');
    }
};
