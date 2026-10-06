<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the initial identity and session tables.
     */
    public function up(): void
    {
        Schema::create('core.users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 150);
            $table->string('email', 254)->unique();
            $table->timestampTz('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestampsTz();
        });

        Schema::create('core.password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 254)->primary();
            $table->string('token');
            $table->timestampTz('created_at')->nullable();
        });

        Schema::create('system.sessions', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained(table: 'core.users')
                ->nullOnDelete();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Drop the initial identity and session tables.
     */
    public function down(): void
    {
        Schema::dropIfExists('system.sessions');
        Schema::dropIfExists('core.password_reset_tokens');
        Schema::dropIfExists('core.users');
    }
};
