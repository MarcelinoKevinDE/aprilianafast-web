<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['pelanggan', 'layanan'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pelanggan', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            })->orWhere('kode_qr', 'like', "%{$search}%");
        }

        $bookings = $query->paginate(15)->withQueryString();

        $stats = [
            'total'         => Booking::count(),
            'menunggu'      => Booking::where('status', 'menunggu')->count(),
            'terkonfirmasi' => Booking::where('status', 'terkonfirmasi')->count(),
            'terverifikasi' => Booking::where('status', 'terverifikasi')->count(),
        ];

        return view('admin.dashboard', compact('bookings', 'stats'));
    }

    /**
     * Admin mengubah status booking secara manual (menunggu -> terkonfirmasi).
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => ['required', 'in:menunggu,terkonfirmasi,terverifikasi'],
        ]);

        $booking->update(['status' => $request->status]);

        return back()->with('status', 'Status booking berhasil diperbarui.');
    }
}

