<?php namespace AppUser\Profile\Http\Middleware;

use Closure;
use AppUser\Profile\Models\User;
use Illuminate\Http\Request;
use Exception;

class ProfileMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Retrieve the bearer token from the request
        $token = $request->bearerToken();

        // Check if a token is provided in the request
        if (!$token) {
            // If no token is provided, throw an exception with a 400 status code
            throw new Exception('No token provided', 400);
        }

        // Find the user associated with the provided token
        $user = User::where('token', $token)->first();

        // Check if the token is invalid (no user found)
        if (!$user) {
            // If the token is invalid, throw an exception with a 400 status code
            throw new Exception('Invalid token', 400);
        }

        // Merge the user data into the request for further processing
        $request->merge(['user' => $user]);

        // Proceed to the next middleware or request handler
        return $next($request);
    }
}
