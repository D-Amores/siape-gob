<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si el usuario está autenticado
        if (Auth::check()) {
            $user = Auth::user();

            // Si el usuario fue desactivado, cerrar sesión
            if (!$user->is_active) {
                Auth::logout(); // Cierra sesión
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Si la petición es AJAX, devuélve JSON (útil para paneles admin)
                if ($request->expectsJson()) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'Tu cuenta ha sido desactivada. Se cerró la sesión.'
                    ], 403);
                }

                // Redirigir al login con mensaje
                return redirect()->route('login')
                    ->withErrors(['inactive' => 'Tu cuenta ha sido desactivada.']);
            }
        }

        return $next($request);
    }
}
