<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user->profiles()->where('name', 'Administrador')->exists()) {
            return response()->json(['message' => 'Acesso negado. Apenas administradores podem executar esta ação.'], 403);
        }

        return $next($request);
    }
}
