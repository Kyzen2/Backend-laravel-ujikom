<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_id')->constrained('sesi_presensi')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->timestamp('waktu_scan');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa']);
            $table->boolean('is_valid')->default(false);
            $table->double('lat_siswa', 10, 7)->nullable();
            $table->double('long_siswa', 10, 7)->nullable();
            $table->timestamps();

            $table->unique(['sesi_id', 'siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
