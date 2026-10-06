<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonaRequest;
use App\Services\PersonaService;
use Illuminate\Http\JsonResponse;
use Exception;

class PersonaController extends Controller
{
    protected PersonaService $personaService;

    public function __construct(PersonaService $personaService)
    {
        $this->personaService = $personaService;
    }

    /**
     * Registra una nueva Persona en el núcleo de identidad.
     */
    public function store(StorePersonaRequest $request): JsonResponse
    {
        try {
            // El request ya viene validado y normalizado desde StorePersonaRequest
            $persona = $this->personaService->createPersona($request->validated());
            
            // Recargamos el modelo para obtener el id_persona (UUID) generado por PostgreSQL
            $persona->refresh();

            return response()->json([
                'message' => 'Persona registrada correctamente.',
                'data' => $persona
            ], 201);

        } catch (Exception $e) {
            // Loguear el error para la observabilidad
            \Log::error('Error al crear persona: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Ocurrió un error al registrar la persona.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}