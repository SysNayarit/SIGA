<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            -- Esquema CORE
            CREATE SCHEMA IF NOT EXISTS core;
            ALTER SCHEMA core OWNER TO siga_owner;
            GRANT ALL ON SCHEMA core TO siga_migrator;
            GRANT USAGE ON SCHEMA core TO siga_app;

            -- Esquema INSTITUTIONAL
            CREATE SCHEMA IF NOT EXISTS institutional;
            ALTER SCHEMA institutional OWNER TO siga_owner;
            GRANT ALL ON SCHEMA institutional TO siga_migrator;
            GRANT USAGE ON SCHEMA institutional TO siga_app;

            -- Esquema SYSTEM
            CREATE SCHEMA IF NOT EXISTS system;
            ALTER SCHEMA system OWNER TO siga_owner;
            GRANT ALL ON SCHEMA system TO siga_migrator;
            GRANT USAGE ON SCHEMA system TO siga_app;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('
            DROP SCHEMA IF EXISTS system CASCADE;
            DROP SCHEMA IF EXISTS institutional CASCADE;
            DROP SCHEMA IF EXISTS core CASCADE;
        ');
    }
};