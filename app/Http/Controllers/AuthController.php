<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6',
            'role'      => 'required|in:admin,tenant,customer',
            'tenant_id' => 'nullable|exists:tenants,id'
        ]);

        // Jika role = tenant maka tenant_id harus diisi
        if ($data['role'] === 'tenant' && !$data['tenant_id']) {
            return response()->json([
                'error' => 'tenant_id wajib diisi untuk role tenant'
            ], 422);
        }

        // Hash password
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}