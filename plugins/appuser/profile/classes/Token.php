<?php namespace AppUser\Profile\Classes;

use AppUser\Profile\Models\User;
use Firebase\JWT\JWT;
use Exception;

class Token
{
    protected static $key;

    // Initialize the key from .env
    public static function init()
    {
        if (!self::$key) {
            self::$key = getenv('JWT_SECRET');
        }
    }

    public static function generateToken(User $user)
    {
        self::init();

        // Ensure the JWT secret key is set
        if (!self::$key) {
            throw new Exception('JWT secret key not set in environment.');
        }

        // Create the payload for the JWT token
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
