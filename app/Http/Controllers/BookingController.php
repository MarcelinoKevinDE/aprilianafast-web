<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingController extends Controller
{
    /**
     * Tampilkan form reservasi/booking.
     */
    public function create()
    {
        $layananList = Layanan::where('aktif', true)->orderBy('harga')->get();

        return view('booking.create', compact('layananList'));
    }

    /**
     * Simpan booking baru, generate kode QR unik, redirect ke halaman sukses.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'        => ['required', 'string', 'max:100'],
            'no_hp'       => ['required', 'string', 'max:20'],
            'email'       => ['nullable', 'email', 'max:100'],
            'layanan_id'  => ['required', 'exists:tb_layanan,id'],
            'tanggal'     => ['required', 'date', 'after_or_equal:today'],
            'jam'         => ['required'],
            'catatan'     => ['nullable', 'string', 'max:500'],
        ], [
            'tanggal.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
        ]);

        // Cari pelanggan berdasarkan no_hp, atau buat baru
        $pelanggan = Pelanggan::firstOrCreate(
            ['no_hp' => $validated['no_hp']],
            ['nama' => $validated['nama'], 'email' => $validated['email'] ?? null]
        );

        // Update nama/email kalau berubah
        $pelanggan->update([
            'nama'  => $validated['nama'],
            'email' => $validated['email'] ?? $pelanggan->email,
        ]);

        $booking = Booking::create([
            'pelanggan_id'    => $pelanggan->id,
            'layanan_id'      => $validated['layanan_id'],
            'tanggal_booking' => $validated['tanggal'],
            'jam_booking'     => $validated['jam'],
            'kode_qr'         => Booking::generateKodeQr(),
            'status'          => 'menunggu',
            'catatan'         => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('booking.success', $booking->id);
    }

    /**
     * Halaman sukses booking, menampilkan QR Code.
     */
    public function success(Booking $booking)
    {
        $booking->load(['pelanggan', 'layanan']);

        return view('booking.success', compact('booking'));
    }

    /**
     * Download QR Code sebagai file PNG.
     */
    public function downloadQr(Booking $booking)
    {
        $image = QrCode::format('png')
            ->size(500)
            ->margin(1)
            ->generate($booking->kode_qr);

        return response($image, 200, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => 'attachment; filename="QR-' . $booking->kode_qr . '.png"',
        ]);
    }
}

