<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCampusContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $campusId = $request->header('X-Campus-ID');

        if ($request->user() && $campusId) {
            // Se puede almacenar el plantel activo en el contenedor de solicitudes para posterior uso en Policies
            app()->instance('active_campus_id', $campusId);
        }

        return $next($request);
    }
}