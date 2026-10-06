<?php

namespace App\Services;

use App\Models\Persona;
use Illuminate\Support\Facades\DB;
use Exception;

class PersonaService
{
    /**
     * Crea una nueva persona garantizando la generación transaccional del expediente.
     *
     * @param array $data Datos validados de la persona
     * @return Persona
     * @throws Exception
     */
    public function createPersona(array $data): Persona
    {
        return DB::transaction(function () use ($data) {
            // 1. Generar el número de expediente desde PostgreSQL
            // Se ejecuta la función que bloquea y actualiza el contador de manera segura
            $result = DB::selectOne('SELECT system.next_expediente_number() AS folio');
            
            if (!$result || empty($result->folio)) {
                throw new Exception('No se pudo generar el número de expediente institucional.');
            }

            // 2. Asignar el folio y la fecha de alta al arreglo de datos
            $data['num_expediente'] = $result->folio;
            $data['fecha_alta'] = now();
            $data['estatus'] = 'ACTIVO';

            // 3. Crear y retornar la Persona
            return Persona::create($data);
        });
    }
}