<?php

namespace App\Http\Middleware;

use Closure;

class Web
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if(empty($user)){
            return response()->json(['message' => 'Unauthenticated. Admin role required'], 401);
        }
        return $next($request);
    }
}
