<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'tb_layanan';

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga',
        'aktif',
    ];

    protected $casts = [
        'harga' => 'integer',
        'aktif' => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'layanan_id');
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}

