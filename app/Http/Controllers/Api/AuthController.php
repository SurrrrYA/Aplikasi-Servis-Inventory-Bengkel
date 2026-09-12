<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        Log::info('LOGIN 1 - validasi selesai');

        $user = User::where('email', $request->email)->first();

        Log::info('LOGIN 2 - query user selesai');

        if (!$user) {

            Log::info('LOGIN - user tidak ditemukan');

            return response()->json([
                'message' => 'Email atau password salah',
                'data' => null
            ], 401);
        }

        Log::info('LOGIN 3 - user ditemukan');

        $passwordCorrect = Hash::check(
            $request->password,
            $user->password
        );

        Log::info('LOGIN 4 - Hash::check selesai');

        if (!$passwordCorrect) {

            Log::info('LOGIN - password salah');

            return response()->json([
                'message' => 'Email atau password salah',
                'data' => null
            ], 401);
        }

        Log::info('LOGIN 5 - password benar');

        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;

        Log::info('LOGIN 6 - token selesai');

        return response()->json([
            'message' => 'Login berhasil',

            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],

                'token' => $token
            ]
        ], 200);
    }


    public function logout(Request $request)
    {
        $request->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}