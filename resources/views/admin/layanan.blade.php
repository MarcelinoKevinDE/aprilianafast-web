<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Layanan | Admin AprilianaFast</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { blush: '#F7E9E6', nude: '#FBF6F2', ink: '#4A3A3C', gold: '#C6A15B' },
            fontFamily: { display: ['"Cormorant Garamond"', 'serif'], body: ['"Jost"', 'sans-serif'] },
        } } }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Jost:wght@400;500&display=swap" rel="stylesheet">
</head>
<body class="font-body bg-nude min-h-screen">

    @include('admin.partials.nav')

    <main class="max-w-5xl mx-auto px-6 py-10">
        <h1 class="font-display text-3xl text-ink mb-8">Manajemen Layanan</h1>

        @if (session('status'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif

        <!-- Form Tambah Layanan -->
        <form method="POST" action="{{ route('admin.layanan.store') }}"
              class="bg-white border border-ink/10 rounded-xl p-6 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            @csrf
            <div class="md:col-span-2">
                <label class="block text-xs uppercase tracking-widest text-ink/50 mb-1">Nama Layanan</label>
                <input type="text" name="nama_layanan" required class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-ink/50 mb-1">Harga (Rp)</label>
                <input type="number" name="harga" required min="0" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
            <button class="px-5 py-2.5 rounded-lg bg-ink text-nude text-sm hover:bg-gold transition-colors">+ Tambah</button>
            <div class="md:col-span-4">
                <label class="block text-xs uppercase tracking-widest text-ink/50 mb-1">Deskripsi</label>
                <input type="text" name="deskripsi" class="w-full rounded-lg border border-ink/15 px-3 py-2 text-sm">
            </div>
        </form>

        <!-- Daftar Layanan -->
        <div class="bg-white border border-ink/10 rounded-xl overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-blush/60 text-ink/60 uppercase text-xs tracking-widest">
                    <tr>
                        <th class="text-left px-5 py-3">Nama</th>
                        <th class="text-left px-5 py-3">Harga</th>
                        <th class="text-left px-5 py-3">Status</th>
                        <th class="text-left px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink/5">
                    @forelse ($layananList as $layanan)
                        <tr>
                            <td class="px-5 py-4">
                                <p class="font-medium">{{ $layanan->nama_layanan }}</p>
                                <p class="text-ink/40 text-xs">{{ $layanan->deskripsi }}</p>
                            </td>
                            <td class="px-5 py-4">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 rounded-full text-xs border {{ $layanan->aktif ? 'bg-green-100 text-green-700 border-green-300' : 'bg-gray-100 text-gray-500 border-gray-300' }}">
                                    {{ $layanan->aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('admin.layanan.destroy', $layanan->id) }}" onsubmit="return confirm('Hapus layanan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-ink/40">Belum ada layanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $layananList->links() }}</div>
    </main>
</body>
</html>

