<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    // Show the registration form
    public function showRegistrationForm()
    {
        return view('UserRegistration');
    }



    //  Handle registration
    // Handle registration
public function register(Request $request)
{
    // Validate the form inputs with custom error messages
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'min:3', // Minimum 3 characters for the name
            'regex:/^[a-zA-Z\s]+$/u', // Only allows alphabets and spaces
        ],

        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users',
            'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', // Enhanced email regex validation
        ],

        'password' => [
            'required',
            'string',
            'min:8',                // Minimum 8 characters
            'max:16',               // Maximum 16 characters (optional)
            'confirmed',            // Must match password confirmation field
            'regex:/[a-z]/',        // Must contain at least one lowercase letter
            'regex:/[A-Z]/',        // Must contain at least one uppercase letter
            'regex:/[0-9]/',        // Must contain at least one digit
            'regex:/[@$!%*#?&]/',   // Must contain at least one special character
        ],
    ], [
        // Custom error messages
        'name.required' => 'Name is required.',
        'name.regex' => 'The name can only contain alphabets and spaces.',
        'email.required' => 'Email is required.',
        'email.unique' => 'This email is already registered.',
        'password.required' => 'Password is required.',
        'password.min' => 'The password must be at least 8 characters long.',
        'password.max' => 'The password cannot exceed 16 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
        'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.',
    ]);

    // Create a new user and save it to the database
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password), // Hash the password before saving
    ]);

    // Log the user in after registration
    Auth::login($user);

    // Redirect to login page after registration
    return redirect()->route('UserLogin');
}



    public function UserLogin()
    {
        // if (auth()->check()) {
        //     return redirect()->route('Home'); // If authenticated, redirect to home
        // }

        return view('UserLogin'); // Show the login form if not authenticated
    }
    // Handle login

    public function login(Request $request)
    {
        // Validate the request inputs
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        // Attempt to authenticate the user using credentials
        if (Auth::attempt($credentials)) {
            // Authentication successful
            $user = Auth::user(); // Get authenticated user

            // Generate a Sanctum token for the user
            $token = $user->createToken('auth-token')->plainTextToken;

            // Return token in response
            return response()->json([
                'message' => 'Login successful!',
                'token' => $token, // Returning the token

            ]);
        }

        // Authentication failed
        return response()->json([
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        if (Auth::check()) {
            return redirect()->route('UserLogin');
        }
        // Invalidate the session and regenerate the CSRF token to avoid session fixation attacks
        $request->session()->invalidate();
        $request->session()->regenerateToken();


        // Redirect to login page
        return redirect('UserLogin');
    }

}
