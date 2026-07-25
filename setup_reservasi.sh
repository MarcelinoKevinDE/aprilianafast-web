#!/bin/bash
# ==========================================================
# Setup Script — Sistem Reservasi + QR Code AprilianaFast
# Jalankan dari root folder project Laravel kamu:
#   chmod +x setup_reservasi.sh && ./setup_reservasi.sh
# ==========================================================
set -e

echo "Membuat struktur folder..."
mkdir -p database/migrations
mkdir -p database/seeders
mkdir -p app/Models
mkdir -p app/Http/Controllers/Admin
mkdir -p app/Http/Middleware
mkdir -p config
mkdir -p resources/views/booking
mkdir -p resources/views/admin/partials

echo "Membuat file-file baru..."

cat > 'database/migrations/2026_07_24_000001_create_tb_pelanggan_table.php' << 'FILEEOF'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('no_hp', 20);
            $table->string('email', 100)->nullable();
            $table->timestamps();

            $table->index('no_hp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pelanggan');
    }
};

FILEEOF
echo '  created: database/migrations/2026_07_24_000001_create_tb_pelanggan_table.php'

cat > 'database/migrations/2026_07_24_000002_create_tb_layanan_table.php' << 'FILEEOF'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan', 100);
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('harga')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_layanan');
    }
};

FILEEOF
echo '  created: database/migrations/2026_07_24_000002_create_tb_layanan_table.php'

cat > 'database/migrations/2026_07_24_000003_create_tb_booking_table.php' << 'FILEEOF'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_booking', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pelanggan_id')
                  ->constrained('tb_pelanggan')
                  ->cascadeOnDelete();

            $table->foreignId('layanan_id')
                  ->constrained('tb_layanan')
                  ->cascadeOnDelete();

            $table->date('tanggal_booking');
            $table->time('jam_booking');

            // Kode unik yang di-encode ke dalam QR Code
            $table->string('kode_qr', 64)->unique();

            // menunggu    -> baru dibuat pelanggan, belum ditinjau admin
            // terkonfirmasi -> sudah dikonfirmasi admin (jadwal fix)
            // terverifikasi -> pelanggan sudah datang & QR sudah discan admin
            $table->enum('status', ['menunggu', 'terkonfirmasi', 'terverifikasi'])
                  ->default('menunggu');

            $table->text('catatan')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_booking');
    }
};

FILEEOF
echo '  created: database/migrations/2026_07_24_000003_create_tb_booking_table.php'

cat > 'database/seeders/LayananSeeder.php' << 'FILEEOF'
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

FILEEOF
echo '  created: database/seeders/LayananSeeder.php'

cat > 'app/Models/Pelanggan.php' << 'FILEEOF'
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

FILEEOF
echo '  created: app/Models/Pelanggan.php'

cat > 'app/Models/Layanan.php' << 'FILEEOF'
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

FILEEOF
echo '  created: app/Models/Layanan.php'

cat > 'app/Models/Booking.php' << 'FILEEOF'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'tb_booking';

    protected $fillable = [
        'pelanggan_id',
        'layanan_id',
        'tanggal_booking',
        'jam_booking',
        'kode_qr',
        'status',
        'catatan',
        'verified_at',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'verified_at'     => 'datetime',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    /**
     * Generate kode unik untuk booking baru, dipakai sebagai isi QR Code.
     * Format: APF-YYYYMMDD-XXXXXX (huruf/angka acak).
     */
    public static function generateKodeQr(): string
    {
        do {
            $kode = 'APF-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('kode_qr', $kode)->exists());

        return $kode;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'menunggu'      => 'Menunggu Konfirmasi',
            'terkonfirmasi' => 'Terkonfirmasi',
            'terverifikasi' => 'Terverifikasi (Sudah Datang)',
            default         => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'menunggu'      => 'bg-yellow-100 text-yellow-700 border-yellow-300',
            'terkonfirmasi' => 'bg-blue-100 text-blue-700 border-blue-300',
            'terverifikasi' => 'bg-green-100 text-green-700 border-green-300',
            default         => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }
}

FILEEOF
echo '  created: app/Models/Booking.php'

cat > 'app/Http/Middleware/AdminAuth.php' << 'FILEEOF'
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}

FILEEOF
echo '  created: app/Http/Middleware/AdminAuth.php'

cat > 'app/Http/Controllers/BookingController.php' << 'FILEEOF'
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

FILEEOF
echo '  created: app/Http/Controllers/BookingController.php'

cat > 'app/Http/Controllers/Admin/AdminAuthController.php' << 'FILEEOF'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $adminEmail    = config('admin.email');
        $adminPassword = config('admin.password');

        if (
            $request->email === $adminEmail &&
            $request->password === $adminPassword
        ) {
            $request->session()->regenerate();
            $request->session()->put('admin_logged_in', true);
            $request->session()->put('admin_email', $adminEmail);

            return redirect()->route('admin.dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_logged_in', 'admin_email']);
        $request->session()->regenerate();

        return redirect()->route('admin.login')->with('status', 'Berhasil logout.');
    }
}

FILEEOF
echo '  created: app/Http/Controllers/Admin/AdminAuthController.php'

cat > 'app/Http/Controllers/Admin/DashboardController.php' << 'FILEEOF'
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

FILEEOF
echo '  created: app/Http/Controllers/Admin/DashboardController.php'

cat > 'app/Http/Controllers/Admin/LayananController.php' << 'FILEEOF'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $layananList = Layanan::orderBy('nama_layanan')->paginate(10);

        return view('admin.layanan', compact('layananList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:100'],
            'deskripsi'    => ['nullable', 'string', 'max:500'],
            'harga'        => ['required', 'integer', 'min:0'],
        ]);

        Layanan::create($validated + ['aktif' => true]);

        return back()->with('status', 'Layanan baru berhasil ditambahkan.');
    }

    public function update(Request $request, Layanan $layanan)
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:100'],
            'deskripsi'    => ['nullable', 'string', 'max:500'],
            'harga'        => ['required', 'integer', 'min:0'],
            'aktif'        => ['nullable', 'boolean'],
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        $layanan->update($validated);

        return back()->with('status', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan)
    {
        $layanan->delete();

        return back()->with('status', 'Layanan berhasil dihapus.');
    }
}

FILEEOF
echo '  created: app/Http/Controllers/Admin/LayananController.php'

cat > 'app/Http/Controllers/Admin/ScanController.php' << 'FILEEOF'
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

FILEEOF
echo '  created: app/Http/Controllers/Admin/ScanController.php'

cat > 'config/admin.php' << 'FILEEOF'
<?php

return [
    'email'    => env('ADMIN_EMAIL', 'admin@aprilianafast.com'),
    'password' => env('ADMIN_PASSWORD', 'ubah-password-ini'),
];

FILEEOF
echo '  created: config/admin.php'

cat > 'resources/views/booking/create.blade.php' << 'FILEEOF'
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

FILEEOF
echo '  created: resources/views/booking/create.blade.php'

cat > 'resources/views/booking/success.blade.php' << 'FILEEOF'
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

FILEEOF
echo '  created: resources/views/booking/success.blade.php'

cat > 'resources/views/admin/login.blade.php' << 'FILEEOF'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | AprilianaFast</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { blush: '#F7E9E6', nude: '#FBF6F2', ink: '#4A3A3C', gold: '#C6A15B' },
            fontFamily: { display: ['"Cormorant Garamond"', 'serif'], body: ['"Jost"', 'sans-serif'] },
        } } }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Jost:wght@400;500&display=swap" rel="stylesheet">
</head>
<body class="font-body bg-gradient-to-b from-blush to-nude min-h-screen flex items-center justify-center px-6">

    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <h1 class="font-display text-3xl text-ink">APRILIANA<span class="text-gold">FAST</span></h1>
            <p class="text-xs uppercase tracking-widest text-ink/50 mt-2">Admin Panel</p>
        </div>

        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">{{ session('error') }}</div>
        @endif
        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" class="bg-nude border border-gold/25 rounded-2xl p-8 space-y-5 shadow-sm">
            @csrf
            <div>
                <label class="block text-xs uppercase tracking-widest text-ink/60 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full rounded-lg border border-ink/15 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold/50">
                @error('email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-ink/60 mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full rounded-lg border border-ink/15 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-gold/50">
            </div>
            <button type="submit"
                class="w-full py-3.5 rounded-full bg-ink text-nude text-xs tracking-[0.2em] uppercase font-medium hover:bg-gold transition-colors">
                Masuk
            </button>
        </form>

        <p class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs text-ink/40 hover:text-gold">&larr; Kembali ke Website</a>
        </p>
    </div>
</body>
</html>

FILEEOF
echo '  created: resources/views/admin/login.blade.php'

cat > 'resources/views/admin/dashboard.blade.php' << 'FILEEOF'
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

FILEEOF
echo '  created: resources/views/admin/dashboard.blade.php'

cat > 'resources/views/admin/layanan.blade.php' << 'FILEEOF'
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

FILEEOF
echo '  created: resources/views/admin/layanan.blade.php'

cat > 'resources/views/admin/scan.blade.php' << 'FILEEOF'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code | Admin AprilianaFast</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { blush: '#F7E9E6', nude: '#FBF6F2', ink: '#4A3A3C', gold: '#C6A15B' },
            fontFamily: { display: ['"Cormorant Garamond"', 'serif'], body: ['"Jost"', 'sans-serif'] },
        } } }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Jost:wght@400;500&display=swap" rel="stylesheet">

    <!-- Library pemindai QR Code berbasis webcam -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>
<body class="font-body bg-nude min-h-screen">

    @include('admin.partials.nav')

    <main class="max-w-3xl mx-auto px-6 py-10">
        <div class="text-center mb-8">
            <h1 class="font-display text-3xl text-ink mb-2">Scan QR Code Booking</h1>
            <p class="text-sm text-ink/50">
                Arahkan kamera ke QR Code milik pelanggan untuk memverifikasi kehadiran.
            </p>
        </div>

        <!-- Area kamera -->
        <div class="bg-white border border-ink/10 rounded-2xl p-6">
            <div id="qr-reader" class="rounded-xl overflow-hidden"></div>
            <p id="qr-status" class="text-center text-xs text-ink/40 mt-4">Meminta akses kamera...</p>
        </div>

        <!-- Hasil verifikasi -->
        <div id="result-box" class="hidden mt-6 rounded-2xl p-6 border">
            <div class="flex items-start gap-4">
                <div id="result-icon" class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"></div>
                <div class="flex-1">
                    <p id="result-message" class="font-medium mb-2"></p>
                    <div id="result-detail" class="text-sm space-y-1 text-ink/70"></div>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <button id="btn-resume" class="hidden px-6 py-2.5 rounded-full bg-ink text-nude text-xs uppercase tracking-widest hover:bg-gold transition-colors">
                Scan Berikutnya
            </button>
        </div>
    </main>

    <script>
        const qrStatusEl   = document.getElementById('qr-status');
        const resultBox    = document.getElementById('result-box');
        const resultIcon   = document.getElementById('result-icon');
        const resultMsg    = document.getElementById('result-message');
        const resultDetail = document.getElementById('result-detail');
        const btnResume    = document.getElementById('btn-resume');

        const CSRF_TOKEN = '{{ csrf_token() }}';
        const VERIFY_URL = '{{ route("admin.scan.verify") }}';

        let html5QrCode;
        let isProcessing = false;

        function startScanner() {
            html5QrCode = new Html5Qrcode("qr-reader");

            const config = { fps: 10, qrbox: { width: 260, height: 260 } };

            html5QrCode.start(
                { facingMode: "environment" }, // pakai kamera belakang di HP
                config,
                onScanSuccess,
                () => { /* diabaikan: dipanggil terus saat belum ada QR terdeteksi */ }
            ).then(() => {
                qrStatusEl.textContent = 'Kamera aktif — arahkan ke QR Code pelanggan.';
            }).catch((err) => {
                qrStatusEl.textContent = 'Gagal mengakses kamera: ' + err;
                qrStatusEl.classList.add('text-red-600');
            });
        }

        function onScanSuccess(decodedText) {
            if (isProcessing) return; // cegah scan ganda saat masih memproses
            isProcessing = true;

            html5QrCode.pause(true);
            verifyKode(decodedText);
        }

        async function verifyKode(kodeQr) {
            try {
                const res = await fetch(VERIFY_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ kode_qr: kodeQr }),
                });

                const data = await res.json();
                showResult(res.ok, data);
            } catch (e) {
                showResult(false, { message: 'Terjadi kesalahan koneksi. Coba lagi.' });
            }
        }

        function showResult(success, data) {
            resultBox.classList.remove('hidden');
            btnResume.classList.remove('hidden');

            if (success && data.success) {
                resultBox.className = 'mt-6 rounded-2xl p-6 border bg-green-50 border-green-300';
                resultIcon.className = 'w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-green-500 text-white';
                resultIcon.innerHTML = '✓';
            } else if (data.already_verified) {
                resultBox.className = 'mt-6 rounded-2xl p-6 border bg-yellow-50 border-yellow-300';
                resultIcon.className = 'w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-yellow-500 text-white';
                resultIcon.innerHTML = '!';
            } else {
                resultBox.className = 'mt-6 rounded-2xl p-6 border bg-red-50 border-red-300';
                resultIcon.className = 'w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-red-500 text-white';
                resultIcon.innerHTML = '✕';
            }

            resultMsg.textContent = data.message || 'Terjadi kesalahan.';

            resultDetail.innerHTML = '';
            if (data.booking) {
                const b = data.booking;
                resultDetail.innerHTML = `
                    <p><span class="text-ink/40">Nama:</span> ${b.nama}</p>
                    <p><span class="text-ink/40">No. HP:</span> ${b.no_hp}</p>
                    <p><span class="text-ink/40">Layanan:</span> ${b.layanan}</p>
                    <p><span class="text-ink/40">Jadwal:</span> ${b.tanggal}, ${b.jam} WIB</p>
                    <p><span class="text-ink/40">Kode QR:</span> ${b.kode_qr}</p>
                `;
            }
        }

        btnResume.addEventListener('click', () => {
            resultBox.classList.add('hidden');
            btnResume.classList.add('hidden');
            isProcessing = false;
            html5QrCode.resume();
        });

        window.addEventListener('DOMContentLoaded', startScanner);
    </script>
</body>
</html>

FILEEOF
echo '  created: resources/views/admin/scan.blade.php'

cat > 'resources/views/admin/partials/nav.blade.php' << 'FILEEOF'
<header class="bg-ink text-nude">
    <nav class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}" class="font-display text-xl tracking-widest">
            APRILIANA<span class="text-gold">FAST</span> <span class="text-xs text-nude/50 tracking-normal">Admin</span>
        </a>
        <div class="flex items-center gap-6 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gold transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-gold' : '' }}">Dashboard</a>
            <a href="{{ route('admin.layanan.index') }}" class="hover:text-gold transition-colors {{ request()->routeIs('admin.layanan.*') ? 'text-gold' : '' }}">Layanan</a>
            <a href="{{ route('admin.scan') }}" class="hover:text-gold transition-colors {{ request()->routeIs('admin.scan') ? 'text-gold' : '' }}">Scan QR</a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="text-nude/60 hover:text-gold transition-colors">Logout</button>
            </form>
        </div>
    </nav>
</header>

FILEEOF
echo '  created: resources/views/admin/partials/nav.blade.php'


echo ""
echo "=========================================================="
echo "Selesai! Semua file baru berhasil dibuat."
echo ""
echo "File yang HARUS kamu update MANUAL (sudah ada sebelumnya,"
echo "tidak ditimpa otomatis oleh script ini demi keamanan):"
echo "  - routes/web.php"
echo "  - resources/views/index.blade.php"
echo "  - .env  (tambahkan ADMIN_EMAIL & ADMIN_PASSWORD)"
echo ""
echo "Langkah selanjutnya:"
echo "  1. composer require simplesoftwareio/simple-qrcode"
echo "  2. Daftarkan middleware 'admin.auth' di bootstrap/app.php"
echo "  3. php artisan migrate"
echo "  4. php artisan db:seed --class=LayananSeeder"
echo "=========================================================="