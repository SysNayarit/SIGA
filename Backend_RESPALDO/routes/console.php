<?php

use Illuminate\Support\Facades\Artisan;
use App\Models\Campus;
use App\Models\Persona;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

Artisan::command("test:rbac", function () {
    $campus = Campus::firstOrCreate(["code" => "PLANTEL-01"], ["name" => "Plantel Central SIGA", "short_name" => "Central"]);
    $user = User::where("email", "juan.perez@siga.gob.mx")->firstOrFail();

    // 1. Crear Permiso
    $permiso = Permission::firstOrCreate(
        ["name" => "personas.read"],
        ["module" => "Núcleo", "description" => "Permite consultar registros de personas"]
    );

    // 2. Crear Rol
    $rol = Role::firstOrCreate(
        ["name" => "Administrador Escolar"],
        ["description" => "Administrador del área de control escolar"]
    );

    // 3. Vincular Permiso a Rol
    if (!$rol->permissions()->where("permission_id", $permiso->id)->exists()) {
        $rol->permissions()->attach($permiso->id);
    }

    // 4. Asignar Rol a Usuario asignando el Contexto de Plantel
    if (!$user->roles()->wherePivot("campus_id", $campus->id)->where("role_id", $rol->id)->exists()) {
        $user->roles()->attach($rol->id, ["campus_id" => $campus->id]);
    }

    $this->info("Prueba RBAC completada.");
    $this->info("Usuario: " . $user->name);
    $this->info("Rol asignado: " . $rol->name . " en Plantel: " . $campus->name);
    $this->info("Tiene permiso personas.read en Plantel: " . ($user->hasPermission("personas.read", $campus->id) ? "SI" : "NO"));
});

