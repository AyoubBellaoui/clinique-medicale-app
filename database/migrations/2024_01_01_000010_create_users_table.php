<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
        $table->id();

        $table->string('email')->unique();
        $table->string('password');

        $table->string('role')->default('medecin');

        $table->foreignId('staff_id')
            ->nullable()
            ->constrained('staff_medicals')
            ->nullOnDelete();

        $table->rememberToken();
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};


