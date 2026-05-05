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
        Schema::table('staff_medicals', function (Blueprint $table) {
            $table->string('license_number')->nullable()->after('specialite');
        });
    }

    public function down(): void
    {
        Schema::table('staff_medicals', function (Blueprint $table) {
            $table->dropColumn('license_number');
        });
    }
};
