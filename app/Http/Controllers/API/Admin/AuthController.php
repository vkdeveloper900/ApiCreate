<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\Auth\LoginUserRequest;
use App\Http\Requests\Api\Admin\Auth\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function League\Uri\UriTemplate\first;

class AuthController extends Controller
{

    public function signup(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User registration failed'
            ], 500);
        }
    }

    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();


        $user = User::where('email', $validated['email'])->first();


        if ($user && Hash::check($validated['password'], $user->password)) {

            // Revoke all previous tokens (single login)
            $user->tokens()->delete();
            // Login - token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'data' => [
                    'user' => $user,
                    'auth_token' => $token
                ]
            ]);
        }

        // Login failed
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password.'
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }


}
