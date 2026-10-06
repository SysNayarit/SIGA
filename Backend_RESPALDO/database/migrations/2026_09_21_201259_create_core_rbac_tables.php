<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla de Roles
        Schema::create('core.roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 50)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Tabla de Permisos
        Schema::create('core.permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100)->unique(); // Ej: personas.create, alumnos.read
            $table->string('module', 50);          // Ej: Control Escolar, Admisión
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        // 3. Pivote Role - Permission
        Schema::create('core.role_permission', function (Blueprint $table) {
            $table->foreignUuid('role_id')->constrained('core.roles')->cascadeOnDelete();
            $table->foreignUuid('permission_id')->constrained('core.permissions')->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        // 4. Pivote User - Role (Soporte Multiplantel)
        Schema::create('core.user_role', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained('core.users')->cascadeOnDelete();
            $table->foreignUuid('role_id')->constrained('core.roles')->cascadeOnDelete();
            $table->foreignUuid('campus_id')->nullable()->constrained('core.campuses')->cascadeOnDelete();
            $table->primary(['user_id', 'role_id', 'campus_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core.user_role');
        Schema::dropIfExists('core.role_permission');
        Schema::dropIfExists('core.permissions');
        Schema::dropIfExists('core.roles');
    }
};