<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $table = 'system.counters';
    
    // Al ser una llave primaria compuesta, Eloquent requiere estas configuraciones
    protected $primaryKey = ['counter_type', 'year'];
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'counter_type',
        'year',
        'last_value',
    ];

    protected $casts = [
        'year' => 'integer',
        'last_value' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}