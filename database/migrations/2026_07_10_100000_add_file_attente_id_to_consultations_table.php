<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->foreignId('file_attente_id')
                ->nullable()
                ->after('staff_id')
                ->constrained('file_attentes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['file_attente_id']);
            $table->dropColumn('file_attente_id');
        });
    }
};
