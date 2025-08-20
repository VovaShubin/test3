<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $provided = $request->header('X-Admin-Token');
        $expected = (string) config('app.admin_api_token', env('ADMIN_API_TOKEN'));

        if ($expected === '' || $provided !== $expected) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}


