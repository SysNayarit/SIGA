<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'institutional.persons';
    protected $primaryKey = 'id_persona';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'curp',
        'rfc',
        'num_expediente',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'sexo',
        'estado_civil',
        'correo_institucional',
        'correo_personal',
        'id_pais_origen',
        'id_pais_nacimiento',
        'id_territorio_nacimiento',
        'fecha_alta',
        'fecha_baja',
        'estatus',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_alta'       => 'datetime',
        'fecha_baja'       => 'datetime',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    // Relaciones hacia catálogos
    public function paisOrigen()
    {
        return $this->belongsTo(Country::class, 'id_pais_origen', 'id_pais');
    }

    public function paisNacimiento()
    {
        return $this->belongsTo(Country::class, 'id_pais_nacimiento', 'id_pais');
    }

    public function territorioNacimiento()
    {
        return $this->belongsTo(Territory::class, 'id_territorio_nacimiento', 'id_territorio');
    }
    
    // Relación al usuario (1:1 según documento maestro)
    public function usuario()
    {
        return $this->hasOne(User::class, 'id_persona', 'id_persona');
    }
}