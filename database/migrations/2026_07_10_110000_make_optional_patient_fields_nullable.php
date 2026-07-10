<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The patient intake form (resources/views/patients/create.blade.php) never
     * collects statut_marital at all, and treats date_naissance/genre/telephone
     * as optional (no required attribute, and the controller validates them as
     * "nullable"). Since the original migration made them NOT NULL with no
     * default, submitting the real form 500s. Relax the schema to match what
     * the form and validation actually promise.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->date('date_naissance')->nullable()->change();
            $table->enum('genre', ['M', 'F'])->nullable()->change();
            $table->enum('statut_marital', ['celibataire', 'marie', 'divorce', 'veuf'])->nullable()->change();
            $table->string('telephone')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->date('date_naissance')->nullable(false)->change();
            $table->enum('genre', ['M', 'F'])->nullable(false)->change();
            $table->enum('statut_marital', ['celibataire', 'marie', 'divorce', 'veuf'])->nullable(false)->change();
            $table->string('telephone')->nullable(false)->change();
        });
    }
};
