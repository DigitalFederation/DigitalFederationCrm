<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (! $token) {
            return response()->json(['message' => 'No API token provided'], 401);
        }

        // Find the token based on the hashed version
        $apiToken = ApiToken::where('token', ApiToken::hashToken($token))->first();

        if (! $apiToken) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (! $apiToken->allowsRoute($request->route()?->getName())) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
