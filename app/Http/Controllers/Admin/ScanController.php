<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ScanController extends Controller
{
    /**
     * Halaman scan QR Code menggunakan webcam.
     */
    public function index()
    {
        return view('admin.scan');
    }

    /**
     * Endpoint AJAX dipanggil dari JS scanner (html5-qrcode) setiap kali
     * berhasil mendecode sebuah QR Code. Mencocokkan kode_qr dengan
     * database, lalu mengubah status booking menjadi 'terverifikasi'.
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'kode_qr' => ['required', 'string'],
        ]);

        $kodeQr = trim($request->kode_qr);

        $booking = Booking::with(['pelanggan', 'layanan'])
            ->where('kode_qr', $kodeQr)
            ->first();

        if (! $booking) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak dikenali. Kode tidak ditemukan di database.',
            ], 404);
        }

        if ($booking->status === 'terverifikasi') {
            return response()->json([
                'success' => false,
                'already_verified' => true,
                'message' => 'QR Code ini sudah pernah diverifikasi sebelumnya pada ' .
                    $booking->verified_at?->format('d M Y, H:i') . '.',
                'booking' => $this->formatBooking($booking),
            ], 409);
        }

        $booking->update([
            'status'      => 'terverifikasi',
            'verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil diverifikasi!',
            'booking' => $this->formatBooking($booking->fresh(['pelanggan', 'layanan'])),
        ]);
    }

    private function formatBooking(Booking $booking): array
    {
        return [
            'kode_qr'      => $booking->kode_qr,
            'nama'         => $booking->pelanggan->nama,
            'no_hp'        => $booking->pelanggan->no_hp,
            'layanan'      => $booking->layanan->nama_layanan,
            'tanggal'      => $booking->tanggal_booking->format('d M Y'),
            'jam'          => substr($booking->jam_booking, 0, 5),
            'status'       => $booking->status,
            'status_label' => $booking->statusLabel(),
        ];
    }
}

