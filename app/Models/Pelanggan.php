<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'tb_pelanggan';

    protected $fillable = [
        'nama',
        'no_hp',
        'email',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'pelanggan_id');
    }
}

