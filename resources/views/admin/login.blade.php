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

