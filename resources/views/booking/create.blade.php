<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Reservasi | AprilianaFast</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        blush: '#F7E9E6', rose: '#E8C2C0', nude: '#FBF6F2',
                        ink: '#4A3A3C', gold: '#C6A15B', goldlt: '#E7D5AC',
                    },
                    fontFamily: {
                        display: ['"Cormorant Garamond"', 'serif'],
                        body: ['"Jost"', 'sans-serif'],
                    },
                },
            },
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

    <main class="max-w-2xl mx-auto px-6 pb-20">
        <div class="text-center mb-10">
            <p class="uppercase tracking-[0.4em] text-xs text-gold mb-3">Reservasi Online</p>
            <h1 class="font-display text-3xl md:text-4xl text-ink">Form Booking Layanan Rias</h1>
            <p class="mt-3 text-ink/60 text-sm">
                Isi data di bawah ini. Setelah booking berhasil, kamu akan mendapatkan
                <span class="text-gold font-medium">QR Code unik</span> sebagai bukti reservasi —
                tunjukkan QR ini saat datang untuk verifikasi.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-300 bg-red-50 text-red-700 text-sm px-5 py-4">
                <p class="font-medium mb-1">Mohon periksa kembali form kamu:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('booking.store') }}" method="POST"
              class="bg-nude border border-gold/25 rounded-2xl p-6 md:p-10 space-y-6 shadow-sm">
            @csrf

            <div>
                <label class="block text-xs uppercase tracking-widest text-ink/60 mb-2">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                    class="w-full rounded-lg border border-rose/60 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                    placeholder="Nama kamu">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-ink/60 mb-2">No. WhatsApp</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                        class="w-full rounded-lg border border-rose/60 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                        placeholder="08xxxxxxxxxx">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-ink/60 mb-2">Email (opsional)</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full rounded-lg border border-rose/60 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                        placeholder="nama@email.com">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-widest text-ink/60 mb-2">Pilih Layanan</label>
                <select name="layanan_id" required
                    class="w-full rounded-lg border border-rose/60 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold/50">
                    <option value="">-- Pilih paket / layanan --</option>
                    @foreach ($layananList as $layanan)
                        <option value="{{ $layanan->id }}" @selected(old('layanan_id') == $layanan->id)>
                            {{ $layanan->nama_layanan }} — Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-ink/60 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal') }}" required
                        min="{{ date('Y-m-d') }}"
                        class="w-full rounded-lg border border-rose/60 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold/50">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-ink/60 mb-2">Jam</label>
                    <input type="time" name="jam" value="{{ old('jam') }}" required
                        class="w-full rounded-lg border border-rose/60 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold/50">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-widest text-ink/60 mb-2">Catatan (opsional)</label>
                <textarea name="catatan" rows="3"
                    class="w-full rounded-lg border border-rose/60 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                    placeholder="Lokasi acara, request khusus, dll.">{{ old('catatan') }}</textarea>
            </div>

            <button type="submit"
                class="w-full py-4 rounded-full bg-ink text-nude text-sm tracking-[0.2em] uppercase font-medium hover:bg-gold transition-colors duration-300">
                Konfirmasi Booking
            </button>
        </form>

        <p class="text-center text-xs text-ink/40 mt-6">
            Butuh bantuan cepat? <a href="https://wa.me/6287712748975" target="_blank" class="text-gold underline">Chat WhatsApp</a>
        </p>
    </main>
</body>
</html>

