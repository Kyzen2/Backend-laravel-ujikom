<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{SesiPresensi, Absensi};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class AttendanceController extends Controller {
    public function createSesi(Request $request) {
        $token = bin2hex(random_bytes(16));
        $sesi = SesiPresensi::create([
            'jadwal_id' => $request->jadwal_id,
            'tanggal' => now()->toDateString(),
            'token_qr' => $token
        ]);
        return response()->json(['status' => 'success', 'token_qr' => $token]);
    }

    public function scanQR(Request $request) {
        /** @var \App\Models\User $user */ 
        $user = Auth::user();

        if (!$user || $user->role !== 'siswa' || !$user->siswa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya siswa yang dapat melakukan absensi!'
            ], 403);
        }

        $sesi = SesiPresensi::where('token_qr', $request->token_qr)->firstOrFail();
        $lokasi = $sesi->jadwal->lokasi;

        $jarak = $this->haversine(
            $request->lat_siswa, 
            $request->long_siswa, 
            $lokasi->latitude, 
            $lokasi->longitude
        );
        
        $isValid = $jarak <= $lokasi->radius;

        Absensi::create([
            'sesi_id' => $sesi->id,
            'siswa_id' => $user->siswa->id,
            'waktu_scan' => now(), 
            'status' => 'hadir',
            'is_valid' => $isValid,
            'lat_siswa' => $request->lat_siswa, 
            'long_siswa' => $request->long_siswa 
        ]);

        return response()->json([
            'status' => $isValid ? 'success' : 'error',
            'message' => $isValid ? 'Absen Berhasil' : 'Anda di luar radius sekolah!',
            'jarak_meter' => round($jarak)
        ]);
    }

    private function haversine($lat1, $lon1, $lat2, $lon2) {
        $r = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2)**2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2)**2;
        return $r * (2 * atan2(sqrt($a), sqrt(1-$a)));
    }

    public function historySiswa() {
        $user = Auth::user();
        
        $history = Absensi::with(['sesi.jadwal.mapel'])
            ->where('siswa_id', $user->siswa->id)
            ->orderBy('waktu_scan', 'desc')
            ->get();

        $summary = [
            'total_hadir' => $history->where('status', 'hadir')->count(),
            'total_izin'  => $history->where('status', 'izin')->count(),
            'total_invalid' => $history->where('is_valid', false)->count(),
        ];

        return response()->json([
            'status' => 'success',
            'summary' => $summary,
            'data' => $history
        ]);
    }
}