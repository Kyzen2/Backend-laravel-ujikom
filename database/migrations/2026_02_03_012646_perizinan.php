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
        Schema::create('perizinan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->date('tgl_izin');
            $table->enum('jenis_izin', ['sakit', 'izin', 'dispen']);
            $table->text('alasan')->nullable();
            $table->string('bukti_gambar')->nullable();
            $table->enum('status_izin', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->foreignId('validator_id')->nullable()->constrained('guru');
            $table->timestamps();
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
