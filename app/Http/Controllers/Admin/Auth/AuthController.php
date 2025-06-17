<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    // login

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:4'
        ]);

        if (Auth::attempt($validated)) {
            return redirect()->route('admin.welcome')->with('success', 'Logged in successfully.');
        }
        return redirect()->back()->with('error', 'The provided credentials do not match our records.');

    }

    //signup

    public function showSignUpForm()
    {
        return view('admin.auth.signup');
    }

    public function signUp(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:4|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($user) {
            return redirect()->route('login')->with('success', 'Account created successfully!');
        }

        return back()->with('error', 'Registration failed. Try again.');
    }

    //logout

    public function logOut()
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('login')->with('success', 'Logged out successfully.');
        }

        return redirect()->route('login');
    }
}
