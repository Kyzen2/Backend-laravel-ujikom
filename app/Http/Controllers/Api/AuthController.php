<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_id' => 'required', // Kita wajibkan kirim Device ID
        ]);

        $user = User::where('email', $request->email)->first();

        // 1. Cek User & Password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // 2. Logika Device ID (Mencegah Multi-device)
        if (is_null($user->device_id)) {
            // Jika pertama kali login, ikat ID perangkatnya
            $user->update(['device_id' => $request->device_id]);
        } else {
            // Jika sudah terikat, pastikan device-nya sama
            if ($user->device_id !== $request->device_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akun ini sudah terdaftar di perangkat lain.'
                ], 403);
            }
        }

        // 3. Buat Token Sanctum
        $token = $user->createToken($request->device_id)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role, // Penting untuk navigasi di Apps
                ]
            ]
        ]);
    }

    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil logout'
        ]);
    }
}
