<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Territory extends Model
{
    protected $table = 'institutional.territories';
    protected $primaryKey = 'id_territorio';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_territorio',
        'id_pais',
        'id_territorio_padre',
        'tipo_territorio',
        'clave_oficial',
        'nombre',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function country()
    {
        return $this->belongsTo(Country::class, 'id_pais', 'id_pais');
    }

    public function parent()
    {
        return $this->belongsTo(Territory::class, 'id_territorio_padre', 'id_territorio');
    }

    public function children()
    {
        return $this->hasMany(Territory::class, 'id_territorio_padre', 'id_territorio');
    }
}