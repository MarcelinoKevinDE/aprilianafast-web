<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Layanan;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanan = [
            [
                'nama_layanan' => 'Lily Package',
                'deskripsi'    => 'Makeup Akad, Sepasang Baju Akad, Bouquet Bunga, Free Softlens.',
                'harga'        => 2500000,
            ],
            [
                'nama_layanan' => 'Daisy Package',
                'deskripsi'    => 'Makeup Akad Lanjut Resepsi (Satu Waktu), Sepasang Baju Akad & Resepsi, Bouquet Bunga, Melati Modern, Free Softlens.',
                'harga'        => 4500000,
            ],
            [
                'nama_layanan' => 'Peony Package',
                'deskripsi'    => 'Makeup Akad Lanjut Resepsi (Satu Waktu), Sepasang Baju Akad & Resepsi, Bouquet Bunga, Melati Modern, Henna & Fake Nails, Makeup + Baju 2 Ibu, Beskap/Jas 2 Bapak, Free Softlens.',
                'harga'        => 6500000,
            ],
            [
                'nama_layanan' => 'Makeup Artistry (Reguler)',
                'deskripsi'    => 'Riasan wajah profesional untuk prewedding maupun acara spesial.',
                'harga'        => 750000,
            ],
            [
                'nama_layanan' => 'Event Makeup',
                'deskripsi'    => 'Riasan untuk wisuda, gala dinner, atau sesi foto profesional.',
                'harga'        => 500000,
            ],
        ];

        foreach ($layanan as $item) {
            Layanan::firstOrCreate(
                ['nama_layanan' => $item['nama_layanan']],
                $item
            );
        }
    }
}

