<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class WebJwtAuth
{
    public function handle(Request $request, Closure $next): Response
{
    try {
        $cookieToken = $request->cookie('jwt_token');
        $headerToken = $request->header('Authorization');
        $queryToken = $request->query('token');

        Log::debug('Cookie Token: ' . ($cookieToken ? 'Presente' : 'Ausente'));
        Log::debug('Header Token: ' . ($headerToken ? 'Presente' : 'Ausente'));
        Log::debug('Query Token: ' . ($queryToken ? 'Presente' : 'Ausente'));

        if ($headerToken && strpos($headerToken, 'Bearer ') === 0) {
            $headerToken = substr($headerToken, 7);
        }

        $token = $cookieToken ?? $headerToken ?? $queryToken;

        if (!$token) {
            Log::debug('Redirecionando: Nenhum token encontrado');
            return redirect()->route('login.form');
        }

        JWTAuth::setToken($token);
        $user = JWTAuth::authenticate();
        if (!$user) {
            throw new JWTException('User not found');
        }

    } catch (TokenExpiredException $e) {
        return redirect()->route('login.form')->with('error', 'Token expirado');
    } catch (TokenInvalidException $e) {
        return redirect()->route('login.form')->with('error', 'Token inválido');
    } catch (JWTException $e) {
        return redirect()->route('login.form')->with('error', 'Token não encontrado');
    }

    return $next($request);
}
}
