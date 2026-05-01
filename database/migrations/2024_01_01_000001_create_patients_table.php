<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');

            // Better control
            $table->enum('genre', ['M', 'F']);
            $table->enum('statut_marital', [
                'celibataire',
                'marie',
                'divorce',
                'veuf'
            ]);

            $table->string('cin')->unique();
            $table->string('telephone');
            $table->string('adresse')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
