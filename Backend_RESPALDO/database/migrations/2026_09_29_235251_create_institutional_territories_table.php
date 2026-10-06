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
        Schema::create('institutional.territories', function (Blueprint $table) {
            $table->uuid('id_territorio')->primary();
            $table->uuid('id_pais');
            $table->uuid('id_territorio_padre')->nullable();
            $table->string('tipo_territorio', 25);
            $table->string('clave_oficial', 20)->nullable();
            $table->string('nombre', 150);
            $table->boolean('activo')->default(true);
            $table->timestampsTz();

            // Constraint único necesario para permitir la llave foránea compuesta del padre
            $table->unique(['id_territorio', 'id_pais']);

            // Llaves foráneas
            $table->foreign('id_pais')->references('id_pais')->on('institutional.countries');
            
            // Llave foránea compuesta: asegura que el padre pertenezca al mismo país
            $table->foreign(['id_territorio_padre', 'id_pais'])
                  ->references(['id_territorio', 'id_pais'])
                  ->on('institutional.territories');
        });

        // Constraint CHECK: un territorio no puede ser padre de sí mismo
        DB::statement('ALTER TABLE institutional.territories ADD CONSTRAINT check_no_self_parent CHECK (id_territorio != id_territorio_padre);');

        // Asignación de roles y permisos técnicos en PostgreSQL
        DB::statement('ALTER TABLE institutional.territories OWNER TO siga_owner;');
        DB::statement('GRANT SELECT ON institutional.territories TO siga_app;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutional.territories');
    }
};