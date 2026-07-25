<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'tb_booking';

    protected $fillable = [
        'pelanggan_id',
        'layanan_id',
        'tanggal_booking',
        'jam_booking',
        'kode_qr',
        'status',
        'catatan',
        'verified_at',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'verified_at'     => 'datetime',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    /**
     * Generate kode unik untuk booking baru, dipakai sebagai isi QR Code.
     * Format: APF-YYYYMMDD-XXXXXX (huruf/angka acak).
     */
    public static function generateKodeQr(): string
    {
        do {
            $kode = 'APF-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('kode_qr', $kode)->exists());

        return $kode;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'menunggu'      => 'Menunggu Konfirmasi',
            'terkonfirmasi' => 'Terkonfirmasi',
            'terverifikasi' => 'Terverifikasi (Sudah Datang)',
            default         => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'menunggu'      => 'bg-yellow-100 text-yellow-700 border-yellow-300',
            'terkonfirmasi' => 'bg-blue-100 text-blue-700 border-blue-300',
            'terverifikasi' => 'bg-green-100 text-green-700 border-green-300',
            default         => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }
}

