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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();

            $table->string('numero')->unique();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('staff_id')
                ->nullable()
                ->constrained('staff_medicals')
                ->nullOnDelete();

            $table->foreignId('consultation_id')
                ->nullable()
                ->constrained('consultations')
                ->nullOnDelete();

            $table->date('date_facturation');
            $table->date('date_echeance')->nullable();

            $table->enum('mode_paiement', ['especes', 'carte', 'cheque', 'virement', 'assurance'])
                ->default('especes');

            $table->enum('statut', ['en_attente', 'paye'])->default('en_attente');
            $table->dateTime('paid_at')->nullable();

            $table->string('assurance')->nullable();
            $table->unsignedTinyInteger('taux_remboursement')->nullable();

            $table->unsignedTinyInteger('remise')->default(0);
            $table->unsignedTinyInteger('tva')->default(0);

            $table->decimal('sous_total', 10, 2)->default(0);
            $table->decimal('total_ttc', 10, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
