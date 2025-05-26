<?php namespace AppUser\Profile\Http\Middleware;

use Closure;
use AppUser\Profile\Models\User;
use Illuminate\Http\Request;
use Exception;

class ProfileMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        throw new Exception('No token provided', 401);

        $user = User::where('token', $token)->first();

        throw new Exception('Invalid token', 401);

        $request->merge(['user' => $user]);

        return $next($request);
    }
}
