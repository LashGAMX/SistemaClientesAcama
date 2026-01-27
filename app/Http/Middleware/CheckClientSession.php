<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckClientSession
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si existe la sesión del cliente
        if (!session()->has('Id_cliente')) {
            // Si es petición AJAX, devolver 401
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Sesión expirada'], 401);
            }
            
            // Si es petición normal, redirigir al login
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        return $next($request);
    }
}