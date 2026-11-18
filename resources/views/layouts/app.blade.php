<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Setting::get('site_name', 'Perumahanku') . ' - ' . \App\Models\Setting::get('site_tagline', 'Katalog Perumahan Baru'))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('hero_subtitle', 'Katalog perumahan baru terpercaya dengan skema cicilan KPR dan booking survei lokasi.'))">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
    <meta name="google-site-verification" content="google188b18d98068125c">

    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Open Graph / Sosmed -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', \App\Models\Setting::get('site_name', 'Perumahanku'))">
    <meta property="og:description" content="@yield('meta_description', \App\Models\Setting::get('hero_subtitle'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('meta_image', asset('images/logo.png'))">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', \App\Models\Setting::get('site_name', 'Alam Damai Property'))">
    <meta name="twitter:description" content="@yield('meta_description', \App\Models\Setting::get('hero_subtitle'))">
    <meta name="twitter:image" content="@yield('meta_image', asset('images/logo.png'))">

    <!-- Geo-Targeting Local SEO (Lampung) -->
    <meta name="geo.region" content="ID-LA">
    <meta name="geo.placename" content="Bandar Lampung, Lampung">
    <meta name="geo.position" content="-5.39714;105.26679">
    <meta name="ICBM" content="-5.39714, 105.26679">

    <!-- JSON-LD Structured Data: RealEstateAgent & Organization -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "RealEstateAgent",
      "name": "{{ \App\Models\Setting::get('company_name', 'PT Alam Damai Property') }}",
      "alternateName": "{{ \App\Models\Setting::get('site_name', 'Alam Damai Property') }}",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/logo.png') }}",
      "image": "{{ asset('images/logo.png') }}",
      "description": "{{ \App\Models\Setting::get('hero_subtitle', 'Katalog perumahan baru terpercaya di Provinsi Lampung dengan skema KPR mudah.') }}",
      "telephone": "{{ \App\Models\Setting::get('company_phone', '0721-123456') }}",
      "email": "{{ \App\Models\Setting::get('company_email', 'info@perumahanku.id') }}",
      "hasMap": "{{ \App\Models\Setting::get('google_maps_url', 'https://maps.app.goo.gl/NgnnxiPaY64W2LMR9') }}",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "{{ \App\Models\Setting::get('company_address', 'Kantor Pemasaran Perumahan Alam Damai Residence, Lampung') }}",
        "addressLocality": "Lampung",
        "addressRegion": "Lampung",
        "addressCountry": "ID"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": -5.3805,
        "longitude": 105.1130
      },
      "areaServed": [
        "Kota Bandar Lampung", "Kota Metro", "Kabupaten Lampung Selatan", "Kabupaten Pesawaran", "Kabupaten Pringsewu"
      ],
      "priceRange": "Rp 150.000.000 - Rp 1.500.000.000"
    }
    </script>
    @yield('structured_data')

    <!-- Preconnect Key Origins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <!-- Google Fonts (Non-Render-Blocking with font-display: swap) -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    </noscript>

    <!-- FontAwesome 6 (Asynchronous / Non-Render-Blocking) -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </noscript>

    <!-- Font Display Swap Fallback for Webfonts -->
    <style>
        @font-face {
            font-family: 'Font Awesome 6 Free';
            font-display: swap;
        }
        @font-face {
            font-family: 'Font Awesome 6 Brands';
            font-display: swap;
        }
    </style>

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0284c7', // Kontras WCAG AA 4.6:1
                            600: '#0369a1',
                            700: '#075985',
                            800: '#0c4a6e',
                            900: '#082f49',
                        },
                        nature: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            500: '#059669', // Kontras WCAG AA 4.5:1
                            600: '#047857',
                            700: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">

    @php
        $siteName = \App\Models\Setting::get('site_name', 'Perumahanku');
        $siteTagline = \App\Models\Setting::get('site_tagline', 'Katalog Perumahan Baru');
        $waNum = \App\Models\Setting::get('whatsapp_number', '6281234567890');
        $waClean = preg_replace('/[^0-9]/', '', $waNum);
        if (str_starts_with($waClean, '0')) $waClean = '62' . substr($waClean, 1);
        $companyName = \App\Models\Setting::get('company_name', 'PT Perumahanku Graha Nusantara');
        $companyPhone = \App\Models\Setting::get('company_phone', '021-88997766');
        $companyEmail = \App\Models\Setting::get('company_email', 'info@perumahanku.id');
        $companyAddress = \App\Models\Setting::get('company_address', 'Jl. Boulevard Hijau No. 128');
    @endphp

    <!-- Top Announcement Bar (Slim & Elegant) -->
    <div class="bg-gradient-to-r from-brand-600 via-brand-500 to-nature-600 text-white text-[11px] py-1 px-4 shadow-2xs">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center gap-2">
                <span class="bg-white/20 text-white px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wider uppercase">Promo Perdana</span>
                <span class="font-medium">DP 0% + Bebas Biaya KPR & Sertifikat SHM!</span>
            </div>
            <div class="flex items-center gap-3 text-white/95 text-[11px]">
                <a href="tel:{{ $companyPhone }}" aria-label="Telepon kantor {{ $companyPhone }}" class="hover:text-white flex items-center gap-1 transition">
                    <i class="fa-solid fa-phone text-[10px]"></i> {{ $companyPhone }}
                </a>
                <span class="hidden sm:inline opacity-40">|</span>
                <a href="https://wa.me/{{ $waClean }}?text={{ rawurlencode('Halo Admin ' . $siteName . ', saya ingin konsultasi perumahan baru.') }}" target="_blank" aria-label="WhatsApp konsultasi {{ $waNum }}" class="hover:text-white flex items-center gap-1 transition">
                    <i class="fa-brands fa-whatsapp text-emerald-300"></i> WA: {{ $waNum }}
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Header (Sleek & Compact) -->
    <header class="sticky top-0 z-40 glass-nav border-b border-slate-200/80 transition-all duration-200" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 gap-4">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-center p-1 group-hover:scale-105 transition-transform duration-200 overflow-hidden shrink-0">
                        <img src="{{ asset('images/logo-icon.png') }}" alt="{{ $siteName }}" class="w-full h-full object-contain">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-base sm:text-lg font-extrabold tracking-tight text-slate-900 group-hover:text-brand-600 transition-colors leading-tight">
                            {{ $siteName }}
                        </span>
                        <span class="text-[10px] text-slate-500 font-medium -mt-0.5 hidden xl:block leading-tight">
                            {{ $siteTagline }}
                        </span>
                    </div>
                </a>

                <!-- Desktop Nav (Clean, Single Line, No Wrapping) -->
                <nav class="hidden lg:flex items-center gap-1 xl:gap-1.5" aria-label="Navigasi Utama">
                    <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg text-xs xl:text-[13px] whitespace-nowrap transition-colors {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-50 font-medium' }}">
                        Beranda
                    </a>
                    <a href="{{ route('projects.index') }}" class="px-3 py-1.5 rounded-lg text-xs xl:text-[13px] whitespace-nowrap transition-colors {{ request()->routeIs('projects.*') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-50 font-medium' }}">
                        Katalog Perumahan
                    </a>
                    <a href="{{ route('kpr.index') }}" class="px-3 py-1.5 rounded-lg text-xs xl:text-[13px] whitespace-nowrap transition-colors {{ request()->routeIs('kpr.*') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-50 font-medium' }}">
                        Simulasi KPR
                    </a>
                    <a href="{{ route('blog.index') }}" class="px-3 py-1.5 rounded-lg text-xs xl:text-[13px] whitespace-nowrap transition-colors {{ request()->routeIs('blog.*') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-50 font-medium' }}">
                        Tips & Artikel
                    </a>
                    <a href="{{ route('pages.about') }}" class="px-3 py-1.5 rounded-lg text-xs xl:text-[13px] whitespace-nowrap transition-colors {{ request()->routeIs('pages.about') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-50 font-medium' }}">
                        Tentang Kami
                    </a>
                    <a href="{{ route('pages.contact') }}" class="px-3 py-1.5 rounded-lg text-xs xl:text-[13px] whitespace-nowrap transition-colors {{ request()->routeIs('pages.contact') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-600 hover:text-brand-600 hover:bg-slate-50 font-medium' }}">
                        Kontak
                    </a>
                </nav>

                <!-- CTA Action Buttons (Compact & Proportional, scale on hover) -->
                <div class="hidden sm:flex items-center gap-2 shrink-0">
                    <a href="{{ route('projects.index') }}#booking-section" class="px-3.5 py-2 rounded-xl bg-nature-600 hover:bg-nature-700 hover:scale-105 active:scale-95 text-white font-bold text-xs shadow-xs hover:shadow-sm transition-all duration-200 flex items-center gap-1.5 whitespace-nowrap">
                        <i class="fa-regular fa-calendar-check text-xs"></i>
                        <span>Booking Survei</span>
                    </a>
                    <a href="https://wa.me/{{ $waClean }}?text={{ rawurlencode('Halo Admin ' . $siteName . ', saya ingin konsultasi unit perumahan.') }}" target="_blank" class="px-3 py-2 rounded-xl border border-slate-200 bg-white hover:bg-brand-50 hover:border-brand-200 hover:scale-105 active:scale-95 text-slate-700 hover:text-brand-700 font-semibold text-xs transition-all duration-200 flex items-center gap-1.5 whitespace-nowrap">
                        <i class="fa-brands fa-whatsapp text-nature-600 text-sm"></i>
                        <span>Chat Sales</span>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center lg:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" aria-label="Buka menu navigasi" :aria-expanded="mobileMenuOpen.toString()" class="p-2 rounded-xl text-slate-700 hover:text-slate-900 hover:bg-slate-100 focus:outline-none transition">
                        <i class="fa-solid text-lg" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-cloak class="lg:hidden border-b border-slate-200 bg-white px-4 pt-3 pb-6 space-y-2 shadow-lg">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fa-solid fa-house w-6 text-brand-500"></i> Beranda
            </a>
            <a href="{{ route('projects.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('projects.*') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fa-solid fa-city w-6 text-brand-500"></i> Katalog Perumahan
            </a>
            <a href="{{ route('kpr.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('kpr.*') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fa-solid fa-calculator w-6 text-brand-500"></i> Simulasi KPR
            </a>
            <a href="{{ route('blog.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('blog.*') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fa-solid fa-newspaper w-6 text-brand-500"></i> Tips & Artikel
            </a>
            <a href="{{ route('pages.about') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('pages.about') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fa-solid fa-circle-info w-6 text-brand-500"></i> Tentang Kami
            </a>
            <a href="{{ route('pages.contact') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('pages.contact') ? 'bg-brand-50 text-brand-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                <i class="fa-solid fa-phone w-6 text-brand-500"></i> Kontak
            </a>
            <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                <a href="{{ route('projects.index') }}#booking-section" class="w-full text-center py-2.5 rounded-xl bg-nature-600 hover:bg-nature-700 text-white font-bold text-xs flex items-center justify-center gap-2">
                    <i class="fa-regular fa-calendar-check"></i> Booking Survei
                </a>
                <a href="https://wa.me/{{ $waClean }}" target="_blank" class="w-full text-center py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-xs flex items-center justify-center gap-2">
                    <i class="fa-brands fa-whatsapp text-nature-600 text-base"></i> Chat WhatsApp
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
                <!-- Col 1: Brand & Bio -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-700/60 shadow-xs flex items-center justify-center p-1 overflow-hidden shrink-0">
                            <img src="{{ asset('images/logo-icon.png') }}" alt="{{ $siteName }}" class="w-full h-full object-contain">
                        </div>
                        <span class="text-2xl font-bold text-white tracking-tight">{{ $siteName }}</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Platform katalog perumahan baru terpercaya. Hadir membantu keluarga Indonesia menemukan hunian idaman dengan kemudahan survei lokasi online dan simulasi KPR transparan.
                    </p>
                    <p class="text-xs text-slate-400">
                        Dipersembahkan oleh: <span class="text-slate-200 font-medium">{{ $companyName }}</span>
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <a href="{{ \App\Models\Setting::get('instagram_url', '#') }}" target="_blank" aria-label="Kunjungi akun Instagram {{ $siteName }}" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-500 text-slate-300 hover:text-white flex items-center justify-center transition">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="{{ \App\Models\Setting::get('facebook_url', '#') }}" target="_blank" aria-label="Kunjungi halaman Facebook {{ $siteName }}" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-500 text-slate-300 hover:text-white flex items-center justify-center transition">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="{{ \App\Models\Setting::get('tiktok_url', '#') }}" target="_blank" aria-label="Kunjungi profil TikTok {{ $siteName }}" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-brand-500 text-slate-300 hover:text-white flex items-center justify-center transition">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                        <a href="https://wa.me/{{ $waClean }}" target="_blank" aria-label="Hubungi kami melalui WhatsApp {{ $siteName }}" class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-nature-500 text-slate-300 hover:text-white flex items-center justify-center transition">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Navigasi Cepat -->
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm tracking-wider uppercase">Menu Populer</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-brand-400 transition">Beranda</a></li>
                        <li><a href="{{ route('projects.index') }}" class="hover:text-brand-400 transition">Semua Perumahan</a></li>
                        <li><a href="{{ route('kpr.index') }}" class="hover:text-brand-400 transition">Kalkulator KPR Interaktif</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-brand-400 transition">Tips & Artikel Properti</a></li>
                        <li><a href="{{ route('pages.about') }}" class="hover:text-brand-400 transition">Tentang Kami</a></li>
                        <li><a href="{{ route('pages.contact') }}" class="hover:text-brand-400 transition">Hubungi Sales / Kantor</a></li>
                    </ul>
                </div>

                <!-- Col 3: Area Perumahan (SEO Landing se-Provinsi Lampung) -->
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm tracking-wider uppercase flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-brand-400 text-xs"></i>
                        <span>Cari Wilayah Terdekat</span>
                    </h4>
                    <ul class="space-y-1.5 text-xs text-slate-400">
                        @foreach(\App\Models\Project::LAMPUNG_CITIES as $cityItem)
                            <li>
                                <a href="{{ route('projects.city', \Illuminate\Support\Str::slug($cityItem)) }}" class="hover:text-brand-400 transition flex items-center justify-between py-0.5 group">
                                    <span class="group-hover:translate-x-0.5 transition-transform truncate">Perumahan di {{ $cityItem }}</span>
                                    <i class="fa-solid fa-arrow-right text-[9px] text-slate-600 group-hover:text-brand-400 shrink-0 ml-1"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Col 4: Kantor & Kontak -->
                <div class="space-y-3">
                    <h4 class="text-white font-semibold mb-4 text-sm tracking-wider uppercase">Marketing Gallery</h4>
                    <a href="{{ \App\Models\Setting::get('google_maps_url', 'https://maps.app.goo.gl/NgnnxiPaY64W2LMR9') }}" target="_blank" rel="noopener noreferrer" class="text-xs text-slate-400 hover:text-white transition flex items-start gap-2.5 group">
                        <i class="fa-solid fa-location-dot text-brand-400 group-hover:text-brand-300 mt-1 shrink-0"></i>
                        <span>{{ $companyAddress }} <span class="text-[10px] text-brand-400 block mt-0.5 group-hover:underline"><i class="fa-solid fa-diamond-turn-right text-[9px] mr-1"></i>Buka Rute Google Maps</span></span>
                    </a>
                    <p class="text-xs text-slate-400 flex items-center gap-2.5">
                        <i class="fa-solid fa-phone text-brand-400"></i>
                        <span>{{ $companyPhone }}</span>
                    </p>
                    <p class="text-xs text-slate-400 flex items-center gap-2.5">
                        <i class="fa-solid fa-envelope text-brand-400"></i>
                        <span>{{ $companyEmail }}</span>
                    </p>
                    <p class="text-xs text-slate-400 flex items-center gap-2.5">
                        <i class="fa-regular fa-clock text-nature-400"></i>
                        <span>{{ \App\Models\Setting::get('office_hours', 'Buka Setiap Hari (08.30 - 17.30 WIB)') }}</span>
                    </p>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 text-center sm:flex sm:justify-between sm:items-center text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} {{ $companyName }}. Seluruh hak cipta dilindungi undang-undang.</p>
                <div class="mt-3 sm:mt-0 flex justify-center gap-4">
                    <a href="{{ route('admin.login') }}" class="text-slate-400 hover:text-slate-200">Panel Internal Admin</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp CTA Button (Compact by default, expands smoothly on hover) -->
    <aside aria-label="WhatsApp Contact" class="fixed bottom-5 right-5 z-50">
        <a href="https://wa.me/{{ $waClean }}?text={{ rawurlencode('Halo Sales ' . $siteName . ', saya sedang mencari informasi perumahan.') }}"
           target="_blank"
           aria-label="Konsultasi via WhatsApp"
           class="group flex items-center bg-nature-600 hover:bg-nature-700 text-white p-3 rounded-full shadow-lg shadow-nature-600/30 hover:shadow-xl hover:shadow-nature-600/40 hover:scale-105 active:scale-95 transition-all duration-300 ease-in-out cursor-pointer"
           title="Tanya via WhatsApp">
            <i class="fa-brands fa-whatsapp text-2xl shrink-0 group-hover:scale-110 transition-transform duration-200"></i>
            <span class="max-w-0 overflow-hidden whitespace-nowrap opacity-0 group-hover:max-w-xs group-hover:opacity-100 group-hover:ml-2.5 group-hover:mr-1.5 transition-all duration-300 ease-in-out font-bold text-xs tracking-wide">
                Tanya via WhatsApp
            </span>
        </a>
    </aside>

    @stack('scripts')
</body>
</html>
