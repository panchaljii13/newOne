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
  public function register(Request $request)
  {
      // Validate the form inputs
      $request->validate([
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users',
          'password' => 'required|string|min:8|confirmed',
      ]);

      // Create a new user and save it to the database
      $user = User::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => Hash::make($request->password), // Hash the password before saving
      ]);

      // Log the user in after registration
      Auth::login($user); // Use Auth facade to log in the user

      // Redirect to the homepage or a different page after registration
      return redirect()->route('UserLogin'); // Assuming 'home' is your route name for the homepage
  }


  public function UserLogin()
  {
      return view('UserLogin');
  }

  // Handle login
  public function login(Request $request)
  {
      $credentials = $request->validate([
          'email' => 'required|email',
          'password' => 'required',
      ]);

      if (Auth::attempt($credentials)) {
          // Authentication passed
          return redirect()->intended('/');
      }

      // Authentication failed
      return back()->withErrors([
          'email' => 'The provided credentials do not match our records.',
      ]);
  }
  
}
