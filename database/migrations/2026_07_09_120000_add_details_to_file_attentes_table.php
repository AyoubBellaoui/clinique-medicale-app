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
        Schema::table('file_attentes', function (Blueprint $table) {
            $table->enum('priorite', ['normale', 'haute', 'urgente'])
                ->default('normale')->after('statut');

            $table->enum('type_visite', ['sans_rdv', 'avec_rdv', 'urgence', 'suivi'])
                ->default('sans_rdv')->after('priorite');

            $table->text('motif')->nullable()->after('type_visite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_attentes', function (Blueprint $table) {
            $table->dropColumn(['priorite', 'type_visite', 'motif']);
        });
    }
};
