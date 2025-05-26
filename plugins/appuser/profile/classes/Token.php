<?php namespace AppUser\Profile\Classes;

use AppUser\Profile\Models\User;
use Firebase\JWT\JWT;
use Carbon\Carbon;

class Token
{
    protected static $key;

    // Initialize the secret key from .env
    public static function init()
    {
        if (!self::$key) {
            self::$key = getenv('JWT_SECRET');
        }
    }

    public static function generateToken(User $user)
    {
        self::init();

        $payload = [
            'sub' => $user->id, // User ID
            'exp' => time() + 60 * 60 * 24 // Token expiration time (24 hours)
        ];

        // Encode the payload into a JWT token
        $jwt = JWT::encode($payload, self::$key, 'HS256');

        // Save the token and its expiration in the user's profile
        $user->token = $jwt;

        // Set the token expiration date
        $user->token_expiration = date('Y-m-d H:i:s', $payload['exp']);

        // Save the user model with the new token and expiration
        $user->save();
    }
}
