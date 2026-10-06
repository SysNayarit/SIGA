<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('institutional.countries', function (Blueprint $table) {
            $table->uuid('id_pais')->primary();
            // Códigos únicos obligatorios
            $table->char('codigo_alpha2', 2)->unique();
            $table->char('codigo_alpha3', 3)->unique();
            $table->char('codigo_num3', 3)->unique();
            
            $table->string('nombre', 150);
            
            // Nacionalidades derivadas (opcionales)
            $table->string('nacionalidad_masculina', 100)->nullable();
            $table->string('nacionalidad_femenina', 100)->nullable();
            
            $table->boolean('activo')->default(true);
            
            // Auditoría técnica
            $table->timestampsTz();
        });

        // Asignación de roles y permisos técnicos en PostgreSQL
        DB::statement('ALTER TABLE institutional.countries OWNER TO siga_owner;');
        DB::statement('GRANT SELECT ON institutional.countries TO siga_app;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutional.countries');
    }
};