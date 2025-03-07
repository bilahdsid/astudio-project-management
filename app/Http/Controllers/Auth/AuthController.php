<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register a new user and issue an access token.
     */
    public function register(Request $request)
    {
        try {
            // Validate incoming request
            $validated = $request->validate([
                'first_name' => 'required|string',
                'last_name'  => 'required|string',
                'email'      => 'required|email|unique:users',
                'password'   => 'required|string|min:6',
            ]);

            // Create the user
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name'  => $validated['last_name'],
                'email'      => $validated['email'],
                'password'   => Hash::make($validated['password']),
            ]);

            // Generate an access token for the new user
            $token = $user->createToken('API Token')->accessToken;

            return response()->json([
                'user'  => $user,
                'token' => $token
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Authenticate the user and issue an access token.
     */
    public function login(Request $request)
    {
        try {
            // Validate credentials
            $credentials = $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($credentials)) {
                // Authentication passed
                $user = Auth::user();
                $token = $user->createToken('API Token')->accessToken;

                return response()->json([
                    'user'  => $user,
                    'token' => $token
                ], 200);
            }

            return response()->json(['error' => 'Unauthenticated'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Logout the user by revoking the token.
     */
    public function logout(Request $request)
    {
        try {
            // Revoke the token that was used to authenticate the current request.
            $request->user()->token()->revoke();

            return response()->json([
                'message' => 'Logged out successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Logout failed', 'message' => $e->getMessage()], 500);
        }
    }
}
