<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    use HasFactory;

    protected $table = 'sesi_presensi';
    protected $fillable = ['jadwal_id', 'tanggal', 'token_qr'];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'sesi_id');
    }
}
