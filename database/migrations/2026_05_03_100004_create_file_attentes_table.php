<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('file_attentes', function (Blueprint $table) {
            $table->id();

            $table->dateTime('arrived_at')->useCurrent();
            $table->integer('position')->nullable();

            $table->enum('statut', [
                'en_attente',
                'en_cours',
                'termine',
                'annule'
            ])->default('en_attente');

            // ✅ Link to rendez-vous (corrected)
            $table->foreignId('rendez_vous_id')
                ->nullable()
                ->constrained('rendez_vous')
                ->nullOnDelete();

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
        Schema::dropIfExists('file_attentes');
    }
};
