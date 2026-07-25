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

