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
        // 1. Crear la tabla de contadores en el esquema system
        Schema::create('system.counters', function (Blueprint $table) {
            $table->string('counter_type', 50);
            $table->smallInteger('year');
            $table->bigInteger('last_value');
            $table->timestampsTz();

            $table->primary(['counter_type', 'year']);
        });

        // Asignar propiedad de la tabla
        DB::unprepared('ALTER TABLE system.counters OWNER TO siga_owner;');
        // Nota: No otorgamos permisos de INSERT/UPDATE a siga_app sobre la tabla directamente.

        // 2. Crear la función generadora del expediente
        DB::unprepared("
            CREATE OR REPLACE FUNCTION system.next_expediente_number()
            RETURNS VARCHAR(13)
            LANGUAGE plpgsql
            SECURITY DEFINER
            SET search_path = system, pg_temp
            AS $$
            DECLARE
                v_year SMALLINT;
                v_next_value BIGINT;
                v_expediente VARCHAR(13);
            BEGIN
                -- Obtener el año actual en la zona horaria de la institución
                v_year := EXTRACT(YEAR FROM CURRENT_TIMESTAMP AT TIME ZONE 'America/Mexico_City')::SMALLINT;

                -- UPSERT: Insertar el primer registro del año o incrementar si ya existe, bloqueando la fila
                INSERT INTO system.counters (counter_type, year, last_value, created_at, updated_at)
                VALUES ('EXPEDIENTE', v_year, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
                ON CONFLICT (counter_type, year)
                DO UPDATE SET
                    last_value = system.counters.last_value + 1,
                    updated_at = CURRENT_TIMESTAMP
                RETURNING last_value INTO v_next_value;

                -- Formatear el resultado: YYYY-NNNNNNNN
                v_expediente := v_year::VARCHAR || '-' || LPAD(v_next_value::VARCHAR, 8, '0');

                RETURN v_expediente;
            END;
            $$;
        ");

        // Asignar propiedad y permisos de ejecución de la función
        DB::unprepared('ALTER FUNCTION system.next_expediente_number() OWNER TO siga_owner;');
        DB::unprepared('GRANT EXECUTE ON FUNCTION system.next_expediente_number() TO siga_app;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS system.next_expediente_number();');
        Schema::dropIfExists('system.counters');
    }
};