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
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->string('motif')->nullable()->after('heure');

            $table->enum('type_consultation', [
                'standard',
                'suivi',
                'urgence',
                'controle_post_operatoire',
                'bilan_complet',
            ])->default('standard')->after('motif');

            $table->unsignedSmallInteger('duree')->default(30)->after('type_consultation');

            $table->enum('priorite', ['normale', 'haute', 'urgente'])
                ->default('normale')->after('duree');

            $table->text('notes')->nullable()->after('priorite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->dropColumn(['motif', 'type_consultation', 'duree', 'priorite', 'notes']);
        });
    }
};
