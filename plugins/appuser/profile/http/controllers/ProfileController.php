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

        // Return the generated token
        return[
            'token' => $user->token,
        ];
    }

    public function login(Request $request)
    {   
        // Retrieve all posted data from the request
        $email = $request->input('email');
        $password = $request->input('password');

        // Find the user by their email address
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new Exception('User not found', 404);
        }

        // Check the provided password matches the stored hashed password
        if (!Hash::check($password, $user->password)) {
            throw new Exception('Wrong password', 401);
        }

        // Generate a JWT token for the user and get its expiration
        Token::generateToken($user);

        // Return a success response with the generated token
        return[
            'token' => $user->token
        ];
    }

    public function logout(Request $request)
    {
        // Retrieve the authenticated user from the request
        $authUser = $request->user;

        // Invalidate the user's token by setting it to null
        $authUser->token = null;

        // Save the updated user record to the database
        $authUser->save();

        // Return a success response indicating the user has been logged out
        return null;
    }
}
