<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // 1. Create Tahun Ajaran
        $tahunAjaran = \App\Models\TahunAjaran::create([
            'tahun' => '2025/2026',
            'semester' => 'Ganjil',
            'is_active' => true
        ]);

        // 2. Create Lokasi
        $lokasi = \App\Models\Lokasi::create([
            'nama_lokasi' => 'Lab Komputer',
            'latitude' => -6.2000000,
            'longitude' => 106.8166667,
            'radius' => 50
        ]);

        // 3. Create Teacher (User & Guru)
        $userGuru = \App\Models\User::create([
            'name' => 'Bapak Guru',
            'email' => 'guru1@sekolah.com',
            'password' => bcrypt('guru123'),
            'role' => 'guru'
        ]);

        $guru = \App\Models\Guru::create([
            'user_id' => $userGuru->id,
            'nama_guru' => 'Bapak Guru',
            'nip' => '1234567890'
        ]);

        // 4. Create Kelas
        $kelas = \App\Models\Kelas::create([
            'nama_kelas' => 'XII RPL 1',
            'tahun_ajaran_id' => $tahunAjaran->id,
            'wali_kelas_id' => $guru->id
        ]);

        // 5. Create Subject (Mapel)
        $mapel = \App\Models\Mapel::create(['nama_mapel' => 'Web Programming']);

        // 6. Create Schedule (Jadwal) - IMPORTANT for ID 1
        \App\Models\Jadwal::create([
            'id' => 1, 
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'lokasi_id' => $lokasi->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);
        
        // 7. Create Student (User & Siswa)
        $userSiswa = \App\Models\User::create([
            'name' => 'Murid 1',
            'email' => 'murid1@sekolah.com',
            'password' => bcrypt('murid123'),
            'role' => 'siswa'
        ]);

        \App\Models\Siswa::create([
            'user_id' => $userSiswa->id,
            'nama_siswa' => 'Murid 1',
            'nisn' => '0011223344'
        ]);
    }
}
