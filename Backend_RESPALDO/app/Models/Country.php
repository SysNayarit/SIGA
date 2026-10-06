<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'institutional.countries';
    protected $primaryKey = 'id_pais';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pais',
        'codigo_alpha2',
        'codigo_alpha3',
        'codigo_num3',
        'nombre',
        'nacionalidad_masculina',
        'nacionalidad_femenina',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}