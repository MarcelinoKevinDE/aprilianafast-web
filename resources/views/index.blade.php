<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AprilianaFast | Professional Makeup Artist</title>
    <meta name="description" content="AprilianaFast - Professional Makeup Artist. Wujudkan riasan impianmu dengan sentuhan elegan, natural, dan tahan lama untuk setiap momen berharga.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN + custom config) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        blush:   '#F7E9E6',   // soft pink background
                        rose:    '#E8C2C0',   // deeper soft pink accent
                        nude:    '#FBF6F2',   // near-white nude background
                        ink:     '#4A3A3C',   // warm dark brown-plum text
                        gold:    '#C6A15B',   // gold accent
                        goldlt:  '#E7D5AC',   // light gold
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
<body class="font-body text-ink bg-nude antialiased">

    {{-- ================= NAVBAR ================= --}}
    <header id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-nude/90 backdrop-blur-md border-b border-rose/40">
        <nav class="max-w-7xl mx-auto px-6 lg:px-10 flex items-center justify-between h-20">
            <a href="#home" class="font-display text-2xl md:text-3xl tracking-[0.15em] text-ink">
                APRILIANA<span class="text-gold">FAST</span>
            </a>

            <!-- Desktop Menu -->
            <ul class="hidden lg:flex items-center gap-10 text-sm tracking-wide uppercase font-medium">
                <li><a href="#home" class="nav-link">Home</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#services" class="nav-link">Services</a></li>
                <li><a href="#price" class="nav-link">Price</a></li>
                <li><a href="#portfolio" class="nav-link">Portfolio</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>

            <a href="https://wa.me/6287712748975?text=Halo%20AprilianaFast%2C%20saya%20tertarik%20untuk%20booking%20makeup%20appointment.%20Boleh%20minta%20info%20lebih%20lanjut%20mengenai%20ketersediaan%20jadwal%20dan%20paketnya%3F%20Terima%20kasih"
               target="_blank" rel="noopener"
               class="hidden lg:inline-block px-6 py-2.5 rounded-full bg-ink text-nude text-xs tracking-widest uppercase font-medium hover:bg-gold transition-colors duration-300">
                Appointment
            </a>

            <!-- Hamburger -->
            <button id="hamburger" class="lg:hidden flex flex-col justify-center items-center w-10 h-10 gap-[5px]" aria-label="Toggle menu">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </nav>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden hidden flex-col bg-nude border-t border-rose/40 px-6 py-6 gap-5">
            <a href="#home" class="mobile-nav-link">Home</a>
            <a href="#about" class="mobile-nav-link">About</a>
            <a href="#services" class="mobile-nav-link">Services</a>
            <a href="#price" class="mobile-nav-link">Price</a>
            <a href="#portfolio" class="mobile-nav-link">Portfolio</a>
            <a href="#contact" class="mobile-nav-link">Contact</a>
            <a href="https://wa.me/6287712748975?text=Halo%20AprilianaFast%2C%20saya%20tertarik%20untuk%20booking%20makeup%20appointment.%20Boleh%20minta%20info%20lebih%20lanjut%20mengenai%20ketersediaan%20jadwal%20dan%20paketnya%3F%20Terima%20kasih"
               target="_blank" rel="noopener"
               class="mt-2 text-center px-6 py-3 rounded-full bg-ink text-nude text-xs tracking-widest uppercase font-medium">
                Appointment
            </a>
        </div>
    </header>

    {{-- ================= HERO SECTION ================= --}}
    <section id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-b from-blush via-nude to-nude">
        <!-- decorative gold ring -->
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full border border-gold/40"></div>
        <div class="absolute -bottom-32 -left-20 w-80 h-80 rounded-full bg-rose/30 blur-3xl"></div>

        <div class="relative z-10 text-center px-6 max-w-3xl mx-auto pt-20">
            <p class="uppercase tracking-[0.4em] text-xs md:text-sm text-gold mb-6 fade-up">Makeup &middot; Beauty &middot; Moments</p>
            <h1 class="font-display text-4xl sm:text-6xl md:text-7xl leading-tight text-ink fade-up">
                Welcome to <br class="hidden sm:block"><span class="italic text-gold">AprilianaFast</span>
            </h1>
            <p class="mt-6 text-base md:text-lg tracking-[0.2em] uppercase text-ink/70 fade-up">
                Professional Makeup Artist
            </p>
            <p class="mt-6 text-ink/60 leading-relaxed max-w-xl mx-auto fade-up">
                Menghadirkan riasan yang elegan, natural, dan memesona untuk setiap momen istimewa dalam hidupmu.
            </p>
            <div class="mt-10 fade-up">
                <a href="https://wa.me/6287712748975?text=Halo%20AprilianaFast%2C%20saya%20tertarik%20untuk%20booking%20makeup%20appointment.%20Boleh%20minta%20info%20lebih%20lanjut%20mengenai%20ketersediaan%20jadwal%20dan%20paketnya%3F%20Terima%20kasih"
                   target="_blank" rel="noopener"
                   class="inline-block px-10 py-4 rounded-full bg-gold text-nude text-xs md:text-sm tracking-[0.2em] uppercase font-medium shadow-lg shadow-gold/30 hover:bg-ink transition-colors duration-300">
                    Make Appointment
                </a>
            </div>
        </div>

        <a href="#about" class="absolute bottom-8 left-1/2 -translate-x-1/2 text-ink/50 hover:text-gold transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </a>
    </section>

    {{-- ================= ABOUT SECTION ================= --}}
    <section id="about" class="py-24 md:py-32 bg-nude">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <!-- Logo Placeholder (bulat / circle) -->
            <div class="mx-auto mb-8 w-32 h-32 md:w-40 md:h-40 rounded-full border border-gold/50 p-2">
                <img src="{{ asset('img/logo.png') }}"
                     alt="Logo AprilianaFast"
                     class="w-full h-full rounded-full object-cover border border-gold/30">
            </div>

            <p class="uppercase tracking-[0.4em] text-xs text-gold mb-4">About Us</p>
            <h2 class="font-display text-3xl md:text-5xl text-ink mb-8">The Art of Effortless Beauty</h2>
            <div class="w-16 h-[2px] bg-gold mx-auto mb-8"></div>
            <p class="text-ink/70 leading-loose text-base md:text-lg max-w-3xl mx-auto">
                AprilianaFast lahir dari kecintaan pada dunia <span class="italic text-gold">fashion &amp; makeup</span>
                yang terus berkembang mengikuti tren kecantikan masa kini. Kami percaya bahwa setiap wajah memiliki
                keindahan uniknya sendiri, sehingga setiap sentuhan riasan kami dirancang untuk merayakan karakter
                dan keanggunan alami setiap klien. Dengan perpaduan teknik profesional, produk berkualitas tinggi,
                dan sentuhan personal, kami hadir untuk mewujudkan penampilan terbaikmu di setiap momen berharga —
                dari akad sederhana hingga resepsi megah, dari pemotretan hingga acara formal.
            </p>
        </div>
    </section>

    {{-- ================= SERVICES SECTION ================= --}}
    <section id="services" class="py-24 md:py-32 bg-gradient-to-b from-nude to-blush">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <p class="uppercase tracking-[0.4em] text-xs text-gold mb-4">What We Offer</p>
                <h2 class="font-display text-3xl md:text-5xl text-ink">Our Services</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="service-card">
                    <div class="w-14 h-14 rounded-full bg-blush flex items-center justify-center mb-6 mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <h3 class="font-display text-2xl text-center text-ink mb-3">Makeup Artistry</h3>
                    <p class="text-ink/60 text-center leading-relaxed text-sm">
                        Riasan wajah profesional untuk pengantin, prewedding, maupun acara spesial dengan hasil
                        flawless dan tahan lama sepanjang hari.
                    </p>
                    <div class="text-center mt-6">
                        <a href="https://drive.google.com/file/d/1AEKKEY-l2-ICl-4QO7tCGmWpB6sKyuvu/view?usp=drivesdk" target="_blank" rel="noopener" class="read-more-btn">Read More</a>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="service-card">
                    <div class="w-14 h-14 rounded-full bg-blush flex items-center justify-center mb-6 mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.98-4.756 2.526-7.32.09-.427-.24-.83-.675-.83H5.106M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                    </div>
                    <h3 class="font-display text-2xl text-center text-ink mb-3">Shopping</h3>
                    <p class="text-ink/60 text-center leading-relaxed text-sm">
                        Bantuan pemilihan busana, aksesoris, dan perlengkapan riasan sesuai konsep serta karakter
                        acara yang diinginkan.
                    </p>
                    <div class="text-center mt-6">
                        <a href="https://drive.google.com/file/d/1AEKKEY-l2-ICl-4QO7tCGmWpB6sKyuvu/view?usp=drivesdk" target="_blank" rel="noopener" class="read-more-btn">Read More</a>
                    </div>
                </div>

                <!-- Service 3 -->
                <div class="service-card">
                    <div class="w-14 h-14 rounded-full bg-blush flex items-center justify-center mb-6 mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                        </svg>
                    </div>
                    <h3 class="font-display text-2xl text-center text-ink mb-3">Event Makeup</h3>
                    <p class="text-ink/60 text-center leading-relaxed text-sm">
                        Layanan riasan untuk berbagai acara formal maupun kasual, mulai dari wisuda, gala dinner,
                        hingga sesi foto profesional.
                    </p>
                    <div class="text-center mt-6">
                        <a href="https://drive.google.com/file/d/1AEKKEY-l2-ICl-4QO7tCGmWpB6sKyuvu/view?usp=drivesdk" target="_blank" rel="noopener" class="read-more-btn">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= PRICE LIST SECTION ================= --}}
    <section id="price" class="py-24 md:py-32 bg-blush">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <p class="uppercase tracking-[0.4em] text-xs text-gold mb-4">Investment</p>
                <h2 class="font-display text-3xl md:text-5xl text-ink">Price List</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">

                <!-- LILY PACKAGE -->
                <div class="price-card">
                    <p class="uppercase tracking-[0.3em] text-xs text-gold mb-2">Package</p>
                    <h3 class="font-display text-3xl text-ink mb-1">Lily</h3>
                    <p class="font-display text-2xl text-gold mb-6">Rp 2.500.000</p>
                    <ul class="price-list">
                        <li>Makeup Akad</li>
                        <li>Sepasang Baju Akad</li>
                        <li>Bouquet Bunga</li>
                        <li>Free Softlens</li>
                    </ul>
                </div>

                <!-- DAISY PACKAGE (highlighted) -->
                <div class="price-card price-card-highlight">
                    <p class="uppercase tracking-[0.3em] text-xs text-nude/80 mb-2">Package</p>
                    <h3 class="font-display text-3xl text-nude mb-1">Daisy</h3>
                    <p class="font-display text-2xl text-goldlt mb-6">Rp 4.500.000</p>
                    <ul class="price-list price-list-dark">
                        <li>Makeup Akad Lanjut Resepsi (Satu Waktu)</li>
                        <li>Sepasang Baju Akad &amp; Resepsi</li>
                        <li>Bouquet Bunga</li>
                        <li>Melati Modern</li>
                        <li>Free Softlens</li>
                    </ul>
                </div>

                <!-- PEONY PACKAGE -->
                <div class="price-card">
                    <p class="uppercase tracking-[0.3em] text-xs text-gold mb-2">Package</p>
                    <h3 class="font-display text-3xl text-ink mb-1">Peony</h3>
                    <p class="font-display text-2xl text-gold mb-6">Rp 6.500.000</p>
                    <ul class="price-list">
                        <li>Makeup Akad Lanjut Resepsi (Satu Waktu)</li>
                        <li>Sepasang Baju Akad &amp; Resepsi</li>
                        <li>Bouquet Bunga</li>
                        <li>Melati Modern</li>
                        <li>Henna &amp; Fake Nails</li>
                        <li>Makeup + Baju 2 Ibu</li>
                        <li>Beskap / Jas 2 Bapak</li>
                        <li>Free Softlens</li>
                    </ul>
                </div>
            </div>

            <div class="text-center mt-14">
                <a href="https://drive.google.com/file/d/1AEKKEY-l2-ICl-4QO7tCGmWpB6sKyuvu/view?usp=drivesdk" target="_blank" rel="noopener"
                   class="inline-block px-10 py-4 rounded-full border border-ink text-ink text-xs md:text-sm tracking-[0.2em] uppercase font-medium hover:bg-ink hover:text-nude transition-colors duration-300">
                    Our Complete Price List
                </a>
            </div>
        </div>
    </section>

    {{-- ================= PORTFOLIO SECTION ================= --}}
    <section id="portfolio" class="py-24 md:py-32 bg-nude">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <p class="uppercase tracking-[0.4em] text-xs text-gold mb-4">Gallery</p>
                <h2 class="font-display text-3xl md:text-5xl text-ink">Portfolio</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
                @for ($i = 1; $i <= 8; $i++)
                    <div class="portfolio-item">
                        <img src="{{ asset('img/foto' . $i . '.jpg') }}"
                             alt="Portfolio AprilianaFast {{ $i }}" loading="lazy">
                    </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- ================= CONTACT SECTION ================= --}}
    <section id="contact" class="py-24 md:py-32 bg-gradient-to-b from-blush to-nude">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <p class="uppercase tracking-[0.4em] text-xs text-gold mb-4">Get In Touch</p>
            <h2 class="font-display text-3xl md:text-5xl text-ink mb-16">Contact Us</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <a href="https://maps.app.goo.gl/vcaZA5a7i7PdBwzE9?g_st=ic" target="_blank" rel="noopener" class="contact-card">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gold mb-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    <p class="text-sm uppercase tracking-widest text-ink/60 mb-2">Address</p>
                    <p class="text-ink font-medium text-sm">Depok, West Java, Indonesia</p>
                </a>

                <a href="https://wa.me/6287712748975" target="_blank" rel="noopener" class="contact-card">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gold mb-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                    <p class="text-sm uppercase tracking-widest text-ink/60 mb-2">WhatsApp</p>
                    <p class="text-ink font-medium text-sm">+62 877-1274-8975</p>
                </a>

                <a href="mailto:aprilianafajartok@gmail.com" class="contact-card">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gold mb-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    <p class="text-sm uppercase tracking-widest text-ink/60 mb-2">Email</p>
                    <p class="text-ink font-medium text-sm">aprilianafajartok@gmail.com</p>
                </a>

                <a href="https://www.instagram.com/aprilianafast?igsh=cDBwc2xqNnAybnZ1" target="_blank" rel="noopener" class="contact-card">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gold mb-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 7.5h.008v.008H16.5V7.5zM6.75 3h10.5a3.75 3.75 0 013.75 3.75v10.5a3.75 3.75 0 01-3.75 3.75H6.75A3.75 3.75 0 013 17.25V6.75A3.75 3.75 0 016.75 3zm7.875 9a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z" />
                    </svg>
                    <p class="text-sm uppercase tracking-widest text-ink/60 mb-2">Instagram</p>
                    <p class="text-ink font-medium text-sm">@aprilianafast</p>
                </a>
            </div>
        </div>
    </section>

    {{-- ================= FOOTER ================= --}}
    <footer class="bg-ink text-nude/80 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-10 mb-12">
            <div>
                <h3 class="font-display text-2xl text-nude mb-4">APRILIANA<span class="text-gold">FAST</span></h3>
                <p class="text-sm leading-relaxed text-nude/60">
                    Professional Makeup Artist yang menghadirkan keindahan alami dalam setiap sentuhan riasan,
                    untuk momen-momen berharga dalam hidupmu.
                </p>
            </div>

            <div>
                <h4 class="uppercase tracking-widest text-xs text-gold mb-5">Quick Links</h4>
                <ul class="space-y-3 text-sm text-nude/60">
                    <li><a href="#home" class="hover:text-gold transition-colors">Home</a></li>
                    <li><a href="#about" class="hover:text-gold transition-colors">About</a></li>
                    <li><a href="#services" class="hover:text-gold transition-colors">Services</a></li>
                    <li><a href="#price" class="hover:text-gold transition-colors">Price</a></li>
                    <li><a href="#portfolio" class="hover:text-gold transition-colors">Portfolio</a></li>
                    <li><a href="#contact" class="hover:text-gold transition-colors">Contact</a></li>
                </ul>
            </div>

            <div>
                <h4 class="uppercase tracking-widest text-xs text-gold mb-5">Connect</h4>
                <ul class="space-y-3 text-sm text-nude/60">
                    <li><a href="https://wa.me/6287712748975" target="_blank" rel="noopener" class="hover:text-gold transition-colors">WhatsApp</a></li>
                    <li><a href="mailto:aprilianafajartok@gmail.com" class="hover:text-gold transition-colors">Email</a></li>
                    <li><a href="https://www.instagram.com/aprilianafast?igsh=cDBwc2xqNnAybnZ1" target="_blank" rel="noopener" class="hover:text-gold transition-colors">Instagram</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-nude/10 pt-6 text-center text-xs text-nude/40">
            &copy; 2026 AprilianaFast. All rights reserved.
        </div>
    </footer>

    <script src="{{ asset('js/script.js') }}?v=2"></script>
</body>
</html>