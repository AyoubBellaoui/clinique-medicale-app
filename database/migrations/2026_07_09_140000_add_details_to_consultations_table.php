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
        Schema::table('consultations', function (Blueprint $table) {
            $table->string('motif')->nullable()->after('date_consultation');

            $table->enum('type_consultation', [
                'standard',
                'suivi',
                'urgence',
                'controle_post_operatoire',
                'bilan_complet',
            ])->default('standard')->after('motif');

            $table->unsignedSmallInteger('tension_systolique')->nullable()->after('type_consultation');
            $table->unsignedSmallInteger('tension_diastolique')->nullable()->after('tension_systolique');
            $table->unsignedSmallInteger('frequence_cardiaque')->nullable()->after('tension_diastolique');
            $table->decimal('temperature', 4, 1)->nullable()->after('frequence_cardiaque');
            $table->unsignedTinyInteger('spo2')->nullable()->after('temperature');
            $table->decimal('poids', 5, 1)->nullable()->after('spo2');
            $table->unsignedSmallInteger('taille')->nullable()->after('poids');

            $table->text('traitement')->nullable()->after('diagnostic');

            $table->boolean('ordonnance_demandee')->default(false)->after('notes');
            $table->boolean('scanner_demande')->default(false)->after('ordonnance_demandee');
            $table->boolean('analyse_demandee')->default(false)->after('scanner_demande');
            $table->boolean('kine_demandee')->default(false)->after('analyse_demandee');

            $table->date('prochain_rdv_date')->nullable()->after('kine_demandee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn([
                'motif',
                'type_consultation',
                'tension_systolique',
                'tension_diastolique',
                'frequence_cardiaque',
                'temperature',
                'spo2',
                'poids',
                'taille',
                'traitement',
                'ordonnance_demandee',
                'scanner_demande',
                'analyse_demandee',
                'kine_demandee',
                'prochain_rdv_date',
            ]);
        });
    }
};
