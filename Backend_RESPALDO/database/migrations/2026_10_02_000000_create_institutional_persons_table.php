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
        Schema::create('institutional.persons', function (Blueprint $table) {
            // PK generada por PostgreSQL (asumiendo que uuidv7() está disponible o definido en tu DB)
            $table->uuid('id_persona')->primary()->default(DB::raw('uuidv7()'));
            
            $table->char('curp', 18)->nullable()->unique();
            $table->char('rfc', 13)->nullable()->unique();
            $table->string('num_expediente', 13)->unique(); // YYYY-NNNNNNNN
            
            $table->string('nombres', 150);
            $table->string('apellido_paterno', 100)->nullable();
            $table->string('apellido_materno', 100)->nullable();
            
            $table->date('fecha_nacimiento')->nullable();
            $table->string('sexo', 10)->nullable();
            $table->string('estado_civil', 7)->nullable();
            
            $table->string('correo_institucional', 254)->nullable(); // UNIQUE funcional definido abajo
            $table->string('correo_personal', 254)->nullable();
            
            // Llaves foráneas a catálogos
            $table->uuid('id_pais_origen')->nullable();
            $table->uuid('id_pais_nacimiento')->nullable();
            $table->uuid('id_territorio_nacimiento')->nullable();
            
            // Control institucional
            $table->timestampTz('fecha_alta');
            $table->timestampTz('fecha_baja')->nullable();
            $table->string('estatus', 10)->default('ACTIVO');
            
            // Auditoría técnica
            $table->timestampsTz();

            // Referencias
            $table->foreign('id_pais_origen')->references('id_pais')->on('institutional.countries');
            $table->foreign('id_pais_nacimiento')->references('id_pais')->on('institutional.countries');
            $table->foreign('id_territorio_nacimiento')->references('id_territorio')->on('institutional.territories');
        });

        // 1. Constraints de dominio estricto
        DB::unprepared("ALTER TABLE institutional.persons ADD CONSTRAINT check_sexo CHECK (sexo IN ('MASCULINO', 'FEMENINO'));");
        DB::unprepared("ALTER TABLE institutional.persons ADD CONSTRAINT check_estado_civil CHECK (estado_civil IN ('SOLTERO', 'CASADO'));");

        // 2. Índice funcional para correo institucional único (insensible a mayúsculas/minúsculas)
        DB::unprepared("CREATE UNIQUE INDEX persons_correo_inst_lower_unique ON institutional.persons (lower(correo_institucional));");

        // 3. Propiedad técnica
        DB::unprepared('ALTER TABLE institutional.persons OWNER TO siga_owner;');
        
        // 4. Permisos operativos para la app (a diferencia de los catálogos, aquí la app SÍ inserta y actualiza)
        DB::unprepared('GRANT SELECT, INSERT, UPDATE ON institutional.persons TO siga_app;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutional.persons');
    }
};