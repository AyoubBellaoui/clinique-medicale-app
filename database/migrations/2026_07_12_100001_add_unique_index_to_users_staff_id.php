<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * store()/update() only validated staff_id as exists:staff_medicals,id, so two
     * user accounts could end up linked to the same staff profile (the create/edit
     * dropdowns hide already-taken staff, but that's a UI nicety, not a guarantee).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique('staff_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['staff_id']);
        });
    }
};
