<?php namespace AppUser\Profile\Http\Middleware;

use Closure;
use AppUser\Profile\Models\User;
use Illuminate\Http\Request;

class ProfileMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'No token provided'], 500);
        }

        $user = User::where('token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 500);
        }

        $request->merge(['user' => $user]);

        return $next($request);
    }
}
