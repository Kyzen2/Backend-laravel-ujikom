<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Absensi, Sesi, Jadwal};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function createSesi(Request $request)
    {
        // Validate request
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
        ]);

        $token = bin2hex(random_bytes(16));
        $sesi = Sesi::create([
            'jadwal_id' => $request->jadwal_id,
            'tanggal' => now()->toDateString(),
            'token_qr' => $token
        ]);

        return response()->json([
            'status' => 'success',
            'token_qr' => $token,
            'message' => 'Sesi presensi berhasil dibuat'
        ]);
    }

    public function scanQR(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if user is authenticated (might be null if middleware is disabled)
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        if ($user->role !== 'siswa') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya siswa yang dapat melakukan absensi!'
            ], 403);
        }

        $request->validate([
            'token_qr' => 'required|exists:sesi_presensi,token_qr',
        ]);

        $sesi = Sesi::where('token_qr', $request->token_qr)->first();
        
        if (!$sesi) {
             return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid!'
            ], 404);
        }

        // Check if student record exists
        if (!$user->siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data siswa tidak ditemukan!'
            ], 404);
        }

        // Check if already scanned for this session
        $exists = Absensi::where('sesi_id', $sesi->id)
            ->where('siswa_id', $user->siswa->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah melakukan absensi untuk sesi ini!'
            ], 400);
        }

        Absensi::create([
            'sesi_id' => $sesi->id,
            'siswa_id' => $user->siswa->id,
            'waktu_scan' => now(),
            'status' => 'hadir',
            'is_valid' => true, // Default to true if location check is disabled
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absen Berhasil',
        ]);
    }

    public function historySiswa()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data siswa tidak ditemukan!'
            ], 404);
        }

        $history = Absensi::with(['sesi.jadwal.mapel'])
            ->where('siswa_id', $user->siswa->id)
            ->orderBy('waktu_scan', 'desc')
            ->get();

        $summary = [
            'total_hadir' => $history->where('status', 'hadir')->count(),
            'total_izin'  => $history->where('status', 'izin')->count(),
            'total_sakit' => $history->where('status', 'sakit')->count(),
            'total_alpa'  => $history->where('status', 'alpa')->count(),
        ];

        return response()->json([
            'status' => 'success',
            'summary' => $summary,
            'data' => $history
        ]);
    }
}
