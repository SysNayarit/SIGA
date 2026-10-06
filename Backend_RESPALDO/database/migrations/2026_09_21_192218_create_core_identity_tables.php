<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla de Planteles (Campuses)
        Schema::create('core.campuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 20)->unique(); // Clave del plantel
            $table->string('name', 150);
            $table->string('short_name', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Tabla Base de Personas (Fuente única de verdad)
        Schema::create('core.personas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('curp', 18)->unique()->nullable();
            $table->string('rfc', 13)->unique()->nullable();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('second_last_name', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender', 20)->nullable(); // Ó catálogo
            $table->string('email_personal', 254)->nullable();
            $table->string('phone', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices B-tree para búsquedas frecuentes
            $table->index(['last_name', 'first_name']);
        });

        // 3. Relación Usuario - Persona / Plantel
        Schema::table('core.users', function (Blueprint $table) {
            $table->foreignUuid('persona_id')->nullable()->constrained('core.personas')->nullOnDelete();
            $table->foreignUuid('primary_campus_id')->nullable()->constrained('core.campuses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('core.users', function (Blueprint $table) {
            $table->dropForeign(['persona_id']);
            $table->dropForeign(['primary_campus_id']);
            $table->dropColumn(['persona_id', 'primary_campus_id']);
        });

        Schema::dropIfExists('core.personas');
        Schema::dropIfExists('core.campuses');
    }
};