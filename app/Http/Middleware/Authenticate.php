<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
          // Si es una petición AJAX, devuelve null (responderá con 401)
        if ($request->expectsJson()) {
            return null;
        }

        // Para peticiones normales, redirige al login
        return route('login');
    }
}
