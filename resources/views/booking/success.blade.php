<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Berhasil | AprilianaFast</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500,600;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { blush: '#F7E9E6', rose: '#E8C2C0', nude: '#FBF6F2', ink: '#4A3A3C', gold: '#C6A15B', goldlt: '#E7D5AC' },
                fontFamily: { display: ['"Cormorant Garamond"', 'serif'], body: ['"Jost"', 'sans-serif'] },
            } },
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=2">
</head>
<body class="font-body text-ink bg-gradient-to-b from-blush to-nude min-h-screen">

    <header class="py-8 text-center">
        <a href="{{ route('home') }}" class="font-display text-2xl md:text-3xl tracking-[0.15em] text-ink">
            APRILIANA<span class="text-gold">FAST</span>
        </a>
    </header>

    <main class="max-w-xl mx-auto px-6 pb-20 text-center">
        <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-green-100 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>

        <p class="uppercase tracking-[0.4em] text-xs text-gold mb-3">Booking Berhasil</p>
        <h1 class="font-display text-3xl md:text-4xl text-ink mb-3">Terima kasih, {{ $booking->pelanggan->nama }}!</h1>
        <p class="text-ink/60 text-sm mb-10">
            Simpan atau unduh QR Code di bawah ini. Tunjukkan QR Code ini kepada admin
            saat kamu datang untuk proses verifikasi kehadiran.
        </p>

        <div class="bg-nude border border-gold/25 rounded-2xl p-8 shadow-sm inline-block">
            <div class="bg-white p-4 rounded-xl inline-block">
                {!! QrCode::size(220)->margin(0)->generate($booking->kode_qr) !!}
            </div>
            <p class="mt-4 font-mono text-sm tracking-widest text-ink/70">{{ $booking->kode_qr }}</p>

            <div class="mt-6 pt-6 border-t border-gold/20 text-left space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-ink/50">Layanan</span><span class="font-medium">{{ $booking->layanan->nama_layanan }}</span></div>
                <div class="flex justify-between"><span class="text-ink/50">Tanggal</span><span class="font-medium">{{ $booking->tanggal_booking->format('d M Y') }}</span></div>
                <div class="flex justify-between"><span class="text-ink/50">Jam</span><span class="font-medium">{{ substr($booking->jam_booking, 0, 5) }} WIB</span></div>
                <div class="flex justify-between items-center">
                    <span class="text-ink/50">Status</span>
                    <span class="px-3 py-1 rounded-full text-xs border {{ $booking->statusColor() }}">{{ $booking->statusLabel() }}</span>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('booking.qr.download', $booking->id) }}"
               class="px-8 py-3.5 rounded-full bg-gold text-nude text-xs tracking-[0.2em] uppercase font-medium hover:bg-ink transition-colors">
                Unduh QR Code
            </a>
            <a href="{{ route('home') }}"
               class="px-8 py-3.5 rounded-full border border-ink text-ink text-xs tracking-[0.2em] uppercase font-medium hover:bg-ink hover:text-nude transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </main>
</body>
</html>

