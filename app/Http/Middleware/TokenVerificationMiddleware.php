<?php

namespace App\Http\Middleware;

use App\Helpers\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        $decoded = JWTToken::decodeToken($token);
        if ($decoded == "Unauthorized") {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->headers->set('email', $decoded->email);
        $request->headers->set('id', $decoded->id);
        return $next($request);
    }
}