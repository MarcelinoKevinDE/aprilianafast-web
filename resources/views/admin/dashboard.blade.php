<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | AprilianaFast</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { blush: '#F7E9E6', nude: '#FBF6F2', ink: '#4A3A3C', gold: '#C6A15B' },
            fontFamily: { display: ['"Cormorant Garamond"', 'serif'], body: ['"Jost"', 'sans-serif'] },
        } } }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="font-body bg-nude min-h-screen">

    @include('admin.partials.nav')

    <main class="max-w-7xl mx-auto px-6 py-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="font-display text-3xl text-ink">Dashboard Booking</h1>
                <p class="text-sm text-ink/50">Pantau status reservasi secara real-time.</p>
            </div>
            <a href="{{ route('admin.scan') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gold text-nude text-xs uppercase tracking-widest font-medium hover:bg-ink transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h4.5v4.5h-4.5v-4.5zm0 10.5h4.5v4.5h-4.5v-4.5zm10.5-10.5h4.5v4.5h-4.5v-4.5zm0 6h1.5v1.5h-1.5v-1.5zm3 0h1.5v1.5h-1.5v-1.5zm-3 3h1.5v1.5h-1.5v-1.5zm3 0h1.5v1.5h-1.5v-1.5zm-1.5-1.5h1.5v1.5h-1.5v-1.5z"/>
                </svg>
                Scan QR Webcam
            </a>
        </div>

        @if (session('status'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white border border-ink/10 rounded-xl p-5">
                <p class="text-xs uppercase tracking-widest text-ink/40 mb-1">Total Booking</p>
                <p class="font-display text-3xl text-ink">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white border border-yellow-200 rounded-xl p-5">
                <p class="text-xs uppercase tracking-widest text-yellow-600 mb-1">Menunggu</p>
                <p class="font-display text-3xl text-yellow-700">{{ $stats['menunggu'] }}</p>
            </div>
            <div class="bg-white border border-blue-200 rounded-xl p-5">
                <p class="text-xs uppercase tracking-widest text-blue-600 mb-1">Terkonfirmasi</p>
                <p class="font-display text-3xl text-blue-700">{{ $stats['terkonfirmasi'] }}</p>
            </div>
            <div class="bg-white border border-green-200 rounded-xl p-5">
                <p class="text-xs uppercase tracking-widest text-green-600 mb-1">Terverifikasi</p>
                <p class="font-display text-3xl text-green-700">{{ $stats['terverifikasi'] }}</p>
            </div>
        </div>

        <!-- Filter -->
        <form method="GET" class="flex flex-col sm:flex-row gap-3 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, no HP, atau kode QR..."
                class="flex-1 rounded-lg border border-ink/15 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold/50">
            <select name="status" class="rounded-lg border border-ink/15 px-4 py-2.5 text-sm">
                <option value="">Semua Status</option>
                <option value="menunggu" @selected(request('status')=='menunggu')>Menunggu</option>
                <option value="terkonfirmasi" @selected(request('status')=='terkonfirmasi')>Terkonfirmasi</option>
                <option value="terverifikasi" @selected(request('status')=='terverifikasi')>Terverifikasi</option>
            </select>
            <button class="px-6 py-2.5 rounded-lg bg-ink text-nude text-sm hover:bg-gold transition-colors">Filter</button>
        </form>

        <!-- Table -->
        <div class="bg-white border border-ink/10 rounded-xl overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-blush/60 text-ink/60 uppercase text-xs tracking-widest">
                    <tr>
                        <th class="text-left px-5 py-3">Pelanggan</th>
                        <th class="text-left px-5 py-3">Layanan</th>
                        <th class="text-left px-5 py-3">Jadwal</th>
                        <th class="text-left px-5 py-3">Kode QR</th>
                        <th class="text-left px-5 py-3">Status</th>
                        <th class="text-left px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink/5">
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="px-5 py-4">
                                <p class="font-medium text-ink">{{ $booking->pelanggan->nama }}</p>
                                <p class="text-ink/40 text-xs">{{ $booking->pelanggan->no_hp }}</p>
                            </td>
                            <td class="px-5 py-4">{{ $booking->layanan->nama_layanan }}</td>
                            <td class="px-5 py-4">
                                {{ $booking->tanggal_booking->format('d M Y') }}<br>
                                <span class="text-ink/40 text-xs">{{ substr($booking->jam_booking, 0, 5) }} WIB</span>
                            </td>
                            <td class="px-5 py-4 font-mono text-xs">{{ $booking->kode_qr }}</td>
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 rounded-full text-xs border {{ $booking->statusColor() }}">
                                    {{ $booking->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if ($booking->status !== 'terverifikasi')
                                    <form method="POST" action="{{ route('admin.booking.status', $booking->id) }}" class="flex gap-2">
                                        @csrf @method('PATCH')
                                        @if ($booking->status === 'menunggu')
                                            <input type="hidden" name="status" value="terkonfirmasi">
                                            <button class="text-xs text-blue-600 hover:underline">Konfirmasi</button>
                                        @endif
                                    </form>
                                @else
                                    <span class="text-xs text-green-600">✓ Sudah datang</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-ink/40">Belum ada data booking.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $bookings->links() }}</div>
    </main>
</body>
</html>

