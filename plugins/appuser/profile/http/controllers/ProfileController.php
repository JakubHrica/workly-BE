<?php namespace AppUser\Profile\Http\Controllers;

use AppUser\Profile\Models\User;
use Illuminate\Routing\Controller;
use AppUser\Profile\Classes\Token;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Exception;

class ProfileController extends Controller
{
    public function register(Request $request)
    {
        // Create a new user
        $user = new User();
        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->email = $request->input('email');
        $user->password = $request->input('password');

        // Save the user data
        $user->save();

        // Generate a JWT token for the user and get its expiration
        Token::generateToken($user);

        return[
            'token' => $user->token,
        ];
    }

    public function login(Request $request)
    {
        // Extract email and password from the request
        $data = $request->post();
        
        // Find the user by email
        $user = User::where('email', $data['email'])->first();

        // Check if the user exists and the password is correct
        if (!$user || Hash::check($data['password'], $user->password)) {
        
        throw new Exception('Invalid email or password', 401);
        }

        // Generate a JWT token for the user and get its expiration
        $tokenData = Token::generateToken($user);

        // Update token and expiration
        $user->token = $tokenData['token'];
        $user->token_expiration = $tokenData['expires_at'];
        $user->save();

        // Return a success response with the new token
        return[
            'token' => $user->token
        ];
    }

    public function logout(Request $request)
    {
        // Retrieve the authenticated user from the request
        $user = $request->user;

        // Invalidate the user's token by setting it to null
        $user->token = null;

        // Save the updated user record to the database
        $user->save();

        // Return a success response indicating the user has been logged out
        return null;
    }
}
