<?php namespace AppUser\Profile\Http\Controllers;

use AppUser\Profile\Models\User;
use Illuminate\Routing\Controller;
use AppUser\Profile\Classes\Token;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Exception;

class ProfileController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Extract required fields from the request
            $data = $request->post();

            // Hash the password for secure storage
            $data['password'] = Hash::make($data['password']);

            // Create a new user
            $user = new User($data);

            // Save the user to the database
            $user->save();

            // Generate a JWT token for the user and get its expiration
            $tokenData = Token::generateToken($user);

            // Update token and expiration
            $user->token = $tokenData['token'];
            $user->token_expiration = $tokenData['expires_at'];
            $user->save();

            // Return a success response with the generated token
            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'token' => $user->token,
            ]);
        } catch (Exception $e) {
            return $this->handleException($e, 'Failed to register user');
        }
    }

    public function login(Request $request)
    {
        try {
            // Extract email and password from the request
            $data = $request->post();
            
            // Find the user by email
            $user = User::where('email', $data['email'])->first();

            // Check if the user exists and the password is correct
            if (!$user || !Hash::check($data['password'], $user->password)) {
            // Return an error response if credentials are invalid
                return response()->json([
                    'status' => 'error',
                    'error' => 'Invalid credentials'
                ], 401);
            }

            // Generate a JWT token for the user and get its expiration
            $tokenData = Token::generateToken($user);

            // Update token and expiration
            $user->token = $tokenData['token'];
            $user->token_expiration = $tokenData['expires_at'];
            $user->save();

            // Return a success response with the new token
            return response()->json([
                'status' => 'succes',
                'message' => 'User logged in successfully',
                'token' => $user->token]);
        } catch (Exception $e) {
            return $this->handleException($e, 'Failed to login');
        }
    }

    public function logout(Request $request)
    {
        try {
            // Retrieve the authenticated user from the request
            $user = $request->user;

            // Invalidate the user's token by setting it to null
            $user->token = null;

            // Save the updated user record to the database
            $user->save();

            // Return a success response indicating the user has been logged out
            return response()->json([
                'status' => 'succes',
                'message' => 'Logged out successfully'
            ]);
        } catch (Exception $e) {
            return $this->handleException($e, 'Failed to logout');
        }
    }

    private function handleException(Exception $e, $defaultMessage)
    {
        return response()->json([
            'error' => $defaultMessage,
            'message' => $e->getMessage()
        ], 500);
    }
}
