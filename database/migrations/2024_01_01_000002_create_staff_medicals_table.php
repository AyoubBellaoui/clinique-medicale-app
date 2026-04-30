<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff_medicals', function (Blueprint $table) {
        $table->id();

        $table->string('nom');
        $table->string('prenom');
        $table->string('specialite')->nullable();
        $table->string('cin')->unique()->nullable();
        $table->string('email')->unique()->nullable();
        $table->string('telephone')->nullable();
        $table->text('adresse')->nullable();
        $table->date('date_embauche')->nullable();
        $table->decimal('salaire', 10, 2)->nullable();
        $table->string('role')->nullable();

        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_medicals');
    }
};
