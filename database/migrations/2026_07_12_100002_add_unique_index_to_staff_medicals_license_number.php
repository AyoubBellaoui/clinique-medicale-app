<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * license_number uniqueness was validation-only (unlike cin/email, which are
     * ->unique() at the DB level), so two concurrent submits could race past the
     * validation check and insert duplicate license numbers.
     */
    public function up(): void
    {
        Schema::table('staff_medicals', function (Blueprint $table) {
            $table->unique('license_number');
        });
    }

    public function down(): void
    {
        Schema::table('staff_medicals', function (Blueprint $table) {
            $table->dropUnique(['license_number']);
        });
    }
};
