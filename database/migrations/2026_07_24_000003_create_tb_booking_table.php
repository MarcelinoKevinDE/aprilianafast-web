<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_booking', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pelanggan_id')
                  ->constrained('tb_pelanggan')
                  ->cascadeOnDelete();

            $table->foreignId('layanan_id')
                  ->constrained('tb_layanan')
                  ->cascadeOnDelete();

            $table->date('tanggal_booking');
            $table->time('jam_booking');

            // Kode unik yang di-encode ke dalam QR Code
            $table->string('kode_qr', 64)->unique();

            // menunggu    -> baru dibuat pelanggan, belum ditinjau admin
            // terkonfirmasi -> sudah dikonfirmasi admin (jadwal fix)
            // terverifikasi -> pelanggan sudah datang & QR sudah discan admin
            $table->enum('status', ['menunggu', 'terkonfirmasi', 'terverifikasi'])
                  ->default('menunggu');

            $table->text('catatan')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_booking');
    }
};

