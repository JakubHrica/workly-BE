<?php namespace Jakub\Workly\Http\Controllers;

use Jakub\Workly\Models\Profile;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Response;

class ProfileController
{
    private $key = 'workly_default_token'; // Key for JWT encoding/decoding

    // Method to handle user registration
    public function register(Request $request)
    {
        try {
            $data = $request->all(); // Get all input data from the request

            // Check if a user with the same email already exists
            if (Profile::where('email', $data['email'])->exists()) {
                return Response::json(['error' => 'User already exists'], 409);
            }

            // Hash the password before saving it
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $profile = Profile::create($data); // Create a new profile record

            // Generate a JWT token for the user
            $jwt = $this->generateToken($profile);

            // Return success response with the token
            return Response::json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'token' => $jwt
            ]);
        } catch (\Exception $e) {
            // Handle server errors
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Method to handle user login
    public function login(Request $request)
    {
        try {
            $data = $request->all(); // Get email and password from the request

            // Find the user by email
            $profile = Profile::where('email', $data['email'])->first();

            // Verify the password and generate a token if valid
            if ($profile && password_verify($data['password'], $profile->password)) {
                $jwt = $this->generateToken($profile);

                // Return success response with the token
                return Response::json([
                    'status' => 'success',
                    'message' => 'User logged in successfully',
                    'token' => $jwt
                ]);
            } else {
                // Return error response for invalid credentials
                return Response::json([
                    'status' => 'error',
                    'message' => 'Wrong email or password'
                ], 401);
            }
        } catch (\Exception $e) {
            // Handle server errors
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Method to handle user logout
    public function logout()
    {
        try {
            $user = $this->authenticate(); // Authenticate the user
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401); // Return unauthorized if no user
            }

            // Invalidate the user's token
            $user->token = null;
            $user->token_expires_at = null;
            $user->save();

            // Return success response
            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            // Handle server errors
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Method to get the authenticated user's profile
    public function me(Request $request)
    {
        $user = $this->authenticate($request); // Authenticate the user

        if (!$user) {
            return Response::json(['error' => 'Unauthorized'], 401); // Return unauthorized if no user
        }

        // Return the user's profile data
        return Response::json([
            'id' => $user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email
        ]);
    }

    // Method to authenticate the user using the JWT token
    public function authenticate()
    {
        $authHeader = request()->header('Authorization'); // Get the Authorization header
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return null; // Return null if no token is provided
        }

        $token = substr($authHeader, 7); // Extract the token from the header

        try {
            // Decode the JWT token
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            $profile = Profile::find($decoded->sub); // Find the user by ID in the token

            // Validate the token and its expiration
            if (!$profile || $profile->token !== $token) {
                return null;
            }

            if (strtotime($profile->token_expires_at) < time()) {
                // Token has expired
                return null;
            }

            return $profile; // Return the authenticated user
        } catch (\Exception $e) {
            return null; // Return null if token decoding fails
        }
    }

    // Method to generate a JWT token for a user
    private function generateToken(Profile $profile)
    {
        $payload = [
            'sub' => $profile->id, // User ID
            'exp' => time() + 60 * 60 * 24 // Token expiration time (24 hours)
        ];

        // Encode the payload into a JWT token
        $jwt = JWT::encode($payload, $this->key, 'HS256');

        // Save the token and its expiration in the user's profile
        $profile->token = $jwt;
        $profile->token_expiration = date('Y-m-d H:i:s', $payload['exp']);
        $profile->save();

        return $jwt; // Return the generated token
    }
}
