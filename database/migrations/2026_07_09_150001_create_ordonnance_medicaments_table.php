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
        Schema::create('ordonnance_medicaments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ordonnance_id')
                ->constrained('ordonnances')
                ->cascadeOnDelete();

            $table->string('nom');
            $table->string('posologie')->nullable();
            $table->string('duree')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordonnance_medicaments');
    }
};
