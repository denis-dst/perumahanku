@extends('layouts.app')

@section('title', \App\Models\Setting::get('site_name', 'Perumahanku') . ' - Temukan Perumahan Baru Impian Keluarga')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-brand-50 via-white to-slate-50 pt-12 pb-24 overflow-hidden">
    <!-- Background Decor Rings -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-brand-200/40 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-nature-200/30 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-10">
            <!-- Pill Tag -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-100/80 border border-brand-200 text-brand-700 text-xs font-semibold mb-6">
                <span class="w-2 h-2 rounded-full bg-nature-500 animate-ping"></span>
                <span>Katalog Resmi Primary Property Developer</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight mb-5">
                {{ \App\Models\Setting::get('hero_title', 'Wujudkan Rumah Impian Keluarga Nyaman & Strategis') }}
            </h1>

            <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
                {{ \App\Models\Setting::get('hero_subtitle', 'Pilihan perumahan baru terbaik dengan skema cicilan KPR ringan, siteplan interaktif ber-POV nyata, dan booking survei lokasi semudah satu klik.') }}
            </p>
        </div>

        <!-- Search Bar Card -->
        <div class="max-w-4xl mx-auto bg-white p-4 sm:p-6 rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 relative">
            <form action="{{ route('projects.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Wilayah / Kota -->
                <div>
                    <label for="home_city_select" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i class="fa-solid fa-location-dot text-brand-600 mr-1"></i> Lokasi Wilayah
                    </label>
                    <select id="home_city_select" name="city" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                        <option value="">Semua Kota</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Range Budget -->
                <div>
                    <label for="home_max_price_select" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i class="fa-solid fa-wallet text-nature-600 mr-1"></i> Budget Maksimal
                    </label>
                    <select id="home_max_price_select" name="max_price" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                        <option value="">Semua Harga</option>
                        <option value="200000000">< Rp 200 Juta (Subsidi)</option>
                        <option value="400000000">< Rp 400 Juta</option>
                        <option value="600000000">< Rp 600 Juta</option>
                        <option value="1000000000">< Rp 1 Miliar</option>
                        <option value="2000000000">> Rp 1 Miliar</option>
                    </select>
                </div>

                <!-- Keyword / Cluster -->
                <div>
                    <label for="home_search_query" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i class="fa-solid fa-magnifying-glass text-brand-600 mr-1"></i> Nama Cluster / Jalan
                    </label>
                    <input id="home_search_query" type="text" name="q" placeholder="Contoh: Harmony, Tajur..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                </div>

                <!-- Search Button -->
                <div class="flex items-end">
                    <button type="submit" aria-label="Cari Perumahan" class="w-full bg-gradient-to-r from-brand-600 to-nature-600 hover:from-brand-700 hover:to-nature-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Cari Rumah</span>
                    </button>
                </div>
            </form>

            <!-- Quick City Tags -->
            <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap items-center gap-2 text-xs">
                <span class="text-slate-600 font-semibold">Pencarian Populer:</span>
                @foreach($cities as $c)
                    <a href="{{ route('projects.city', \Illuminate\Support\Str::slug($c)) }}" class="px-3 py-1 rounded-full bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-700 font-medium transition">
                        Rumah di {{ $c }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Metric Badges -->
        <div class="mt-14 max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="p-4 rounded-2xl bg-white/70 border border-slate-200/80 shadow-xs">
                <p class="text-2xl sm:text-3xl font-extrabold text-brand-600">{{ $totalProjects }} Proyek</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Kawasan Siap Huni</p>
            </div>
            <div class="p-4 rounded-2xl bg-white/70 border border-slate-200/80 shadow-xs">
                <p class="text-2xl sm:text-3xl font-extrabold text-nature-600">{{ $availableUnits }} Unit</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Kavling Tersedia</p>
            </div>
            <div class="p-4 rounded-2xl bg-white/70 border border-slate-200/80 shadow-xs">
                <p class="text-2xl sm:text-3xl font-extrabold text-slate-800">100% Legal</p>
                <p class="text-xs text-slate-500 font-medium mt-1">SHM & IMB Terjamin</p>
            </div>
            <div class="p-4 rounded-2xl bg-white/70 border border-slate-200/80 shadow-xs">
                <p class="text-2xl sm:text-3xl font-extrabold text-brand-600">5+ Bank</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Mitra Resmi KPR</p>
            </div>
        </div>
    </div>
</section>

<!-- Section: Featured Projects Showcase -->
<section class="py-16 bg-white border-y border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Pilihan Istimewa</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                    Katalog Perumahan Unggulan
                </h2>
                <p class="text-sm text-slate-500 mt-1 max-w-xl">
                    Jelajahi perumahan baru berfasilitas lengkap dengan harga kompetitif dan skema cicilan KPR fleksibel.
                </p>
            </div>
            <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700 transition">
                <span>Lihat Semua Proyek</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <!-- Project Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredProjects as $p)
                <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:border-brand-300 transition-all duration-300 flex flex-col">
                    <!-- Image Thumbnail -->
                    <div class="relative h-64 overflow-hidden bg-slate-100">
                        @php
                            $imgUrl = $p->primaryImage ? $p->primaryImage->url : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80';
                        @endphp
                        <img src="{{ $imgUrl }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex flex-col gap-1.5 items-start">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/95 text-brand-700 shadow-sm backdrop-blur-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-brand-500"></i> {{ $p->city }}
                            </span>
                            @if($p->is_featured)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wide bg-nature-600 text-white shadow-xs">
                                    ★ Rekomendasi
                                </span>
                            @endif
                        </div>

                        <!-- Available unit count -->
                        <div class="absolute bottom-4 right-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-900/80 text-white backdrop-blur-xs">
                                {{ $p->available_units_count }} Unit Tersedia
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 group-hover:text-brand-600 transition tracking-tight">
                                <a href="{{ route('projects.show', $p->slug) }}">
                                    {{ $p->name }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-1 mt-1">
                                <i class="fa-solid fa-map-pin text-slate-500 mr-1"></i> {{ $p->district ? $p->district . ', ' : '' }}{{ $p->city }}
                            </p>

                            <p class="text-sm text-slate-600 mt-3 line-clamp-2 leading-relaxed">
                                {{ $p->tagline ?? Str::limit($p->description, 110) }}
                            </p>

                            <!-- Facility Snippets -->
                            @if(!empty($p->facilities) && is_array($p->facilities))
                                <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap gap-1.5">
                                    @foreach(array_slice($p->facilities, 0, 3) as $fac)
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-[11px] font-medium">
                                            ✓ {{ Str::limit($fac, 22) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer -->
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[11px] text-slate-500 font-semibold block">Harga Mulai</span>
                                <span class="text-base font-extrabold text-nature-600">
                                    {{ $p->price_range }}
                                </span>
                            </div>

                            <a href="{{ route('projects.show', $p->slug) }}" class="px-4 py-2 rounded-xl bg-brand-50 group-hover:bg-brand-600 text-brand-700 group-hover:text-white font-semibold text-xs transition duration-300 flex items-center gap-1.5">
                                <span>Detail</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-500">
                    <p>Belum ada perumahan yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Section: Fitur Killer "Siteplan Interaktif + POV Unit" -->
<section class="py-20 bg-gradient-to-br from-brand-900 via-slate-900 to-slate-900 text-white relative overflow-hidden">
    <!-- Glow -->
    <div class="absolute -right-20 top-1/2 -translate-y-1/2 w-96 h-96 bg-brand-500/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left Text -->
            <div class="lg:col-span-6 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-nature-500/20 text-nature-400 text-xs font-semibold border border-nature-500/30">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Inovasi Baru di Perumahanku</span>
                </div>

                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                    Siteplan Interaktif dengan <span class="text-brand-400">POV (Point of View)</span> Nyata
                </h2>

                <p class="text-slate-300 text-base leading-relaxed">
                    Tidak perlu lagi menebak bagaimana pemandangan dari teras depan rumah Anda. Di <strong>Perumahanku</strong>, cukup klik kavling yang Anda inginkan di peta siteplan, dan sistem langsung menampilkan:
                </p>

                <div class="space-y-3.5 text-sm text-slate-300">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </div>
                        <span><strong>Foto Sudut Pandang Nyata</strong> — Pemandangan asli arah hadap kavling (taman, clubhouse, matahari pagi, boulevard).</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-nature-500/20 text-nature-400 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-circle-check text-xs"></i>
                        </div>
                        <span><strong>Status Real-time</strong> — Kode warna instan: Hijau (Tersedia), Kuning (Booking), Merah (Terjual).</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-brand-500/20 text-brand-400 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-brands fa-whatsapp text-xs"></i>
                        </div>
                        <span><strong>Direct Booking Kavling</strong> — Langsung hubungi sales khusus untuk unit kavling tersebut.</span>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('projects.show', 'grand-harmony-residence') }}#siteplan" class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-brand-500 to-nature-500 hover:from-brand-600 hover:to-nature-600 font-bold text-sm text-white shadow-lg transition">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <span>Coba Interaktif Siteplan Sekarang</span>
                    </a>
                </div>
            </div>

            <!-- Right Visual Mockup -->
            <div class="lg:col-span-6">
                <div class="relative rounded-3xl bg-slate-800/80 border border-slate-700 p-4 shadow-2xl backdrop-blur-md">
                    <!-- Fake Siteplan with Pin and Tooltip -->
                    <div class="relative rounded-2xl overflow-hidden aspect-video bg-slate-950">
                        <img src="https://images.unsplash.com/photo-1524813686514-a57563d77d66?auto=format&fit=crop&w=1000&q=80" alt="Siteplan Preview" class="w-full h-full object-cover opacity-70">

                        <!-- Animated Kavling Pin -->
                        <div class="absolute top-[45%] left-[50%] -translate-x-1/2 -translate-y-1/2">
                            <div class="relative flex items-center justify-center">
                                <span class="animate-ping absolute inline-flex h-8 w-8 rounded-full bg-emerald-400 opacity-75"></span>
                                <div class="relative w-8 h-8 rounded-full bg-emerald-500 border-2 border-white shadow-lg flex items-center justify-center text-[10px] font-extrabold text-white">
                                    B-05
                                </div>
                            </div>
                        </div>

                        <!-- Interactive POV Card Pop-up Simulation -->
                        <div class="absolute bottom-3 left-3 right-3 bg-white text-slate-800 rounded-2xl p-3.5 shadow-2xl flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=200&q=80" alt="POV Kavling" class="w-16 h-16 rounded-xl object-cover shrink-0 border border-slate-200">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-900">Kavling B-05 (Tipe 45)</span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-700">Tersedia</span>
                                </div>
                                <p class="text-[11px] text-slate-500 truncate mt-0.5">POV: Menghadap taman tematik cluster & gazebo keluarga</p>
                                <span class="text-xs font-bold text-brand-600 block mt-0.5">Rp 520.000.000</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section: Simulasi KPR Cepat -->
<section class="py-20 bg-slate-50" x-data="{
    price: 450000000,
    dpPercent: 10,
    tenor: 20,
    rate: 4.88,
    get dpAmount() {
        return (this.price * this.dpPercent) / 100;
    },
    get loanAmount() {
        return this.price - this.dpAmount;
    },
    get monthlyInstallment() {
        let p = this.loanAmount;
        let r = (this.rate / 100) / 12;
        let n = this.tenor * 12;
        if (p <= 0 || n <= 0) return 0;
        let cicilan = (p * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
        return Math.round(cicilan);
    },
    formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-12">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Perhitungan Transparan</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                Kalkulator Simulasi KPR Cepat
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Hitung estimasi angsuran bulanan rumah impian Anda sesuai budget dan suku bunga bank rekanan terkini.
            </p>
        </div>

        <div class="max-w-4xl mx-auto bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <!-- Sliders Controls -->
                <div class="space-y-6">
                    <!-- Harga Properti -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="home_kpr_price" class="text-xs font-bold uppercase text-slate-700">Harga Properti</label>
                            <span class="text-sm font-extrabold text-brand-600" x-text="formatRupiah(price)"></span>
                        </div>
                        <input type="range" id="home_kpr_price" aria-label="Harga Properti" min="150000000" max="2000000000" step="25000000" x-model.number="price" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-brand-600">
                        <div class="flex justify-between text-[10px] text-slate-500 mt-1 font-medium">
                            <span>150 Jt</span>
                            <span>1 M</span>
                            <span>2 M</span>
                        </div>
                    </div>

                    <!-- Uang Muka (DP) -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="home_kpr_dp" class="text-xs font-bold uppercase text-slate-700">Uang Muka (DP: <span x-text="dpPercent"></span>%)</label>
                            <span class="text-sm font-bold text-slate-700" x-text="formatRupiah(dpAmount)"></span>
                        </div>
                        <input type="range" id="home_kpr_dp" aria-label="Persentase Uang Muka DP" min="0" max="50" step="5" x-model.number="dpPercent" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-nature-600">
                    </div>

                    <!-- Jangka Waktu / Tenor -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="home_kpr_tenor" class="text-xs font-bold uppercase text-slate-700">Jangka Waktu (Tenor)</label>
                            <span class="text-sm font-bold text-slate-700"><span x-text="tenor"></span> Tahun</span>
                        </div>
                        <input type="range" id="home_kpr_tenor" aria-label="Jangka Waktu Tenor" min="5" max="30" step="5" x-model.number="tenor" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-brand-600">
                    </div>

                    <!-- Bunga Bank -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="home_kpr_rate" class="text-xs font-bold uppercase text-slate-700">Estimasi Suku Bunga</label>
                            <span class="text-sm font-bold text-slate-700"><span x-text="rate"></span>% eff. p.a.</span>
                        </div>
                        <input type="range" id="home_kpr_rate" aria-label="Estimasi Suku Bunga" min="3" max="12" step="0.25" x-model.number="rate" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-brand-600">
                    </div>
                </div>

                <!-- Simulation Output Box -->
                <div class="bg-gradient-to-br from-brand-50 to-nature-50 p-6 sm:p-8 rounded-2xl border border-brand-100 flex flex-col justify-between text-center">
                    <div>
                        <span class="text-xs font-bold tracking-wider uppercase text-slate-500">Estimasi Angsuran Bulanan</span>
                        <div class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2 mb-1" x-text="formatRupiah(monthlyInstallment)">
                            Rp 0
                        </div>
                        <span class="text-xs text-slate-500">per bulan (selama <span x-text="tenor"></span> tahun)</span>
                    </div>

                    <div class="my-6 pt-6 border-t border-brand-200/60 space-y-2 text-xs text-slate-600">
                        <div class="flex justify-between">
                            <span>Pokok Pinjaman KPR:</span>
                            <span class="font-bold text-slate-800" x-text="formatRupiah(loanAmount)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Estimasi DP:</span>
                            <span class="font-bold text-slate-800" x-text="formatRupiah(dpAmount)"></span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <a :href="'{{ route('kpr.index') }}?price=' + price + '&dp=' + dpPercent + '&tenor=' + tenor" class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition block">
                            Bandingkan Cicilan per Bank
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section: Tips & Artikel Blog Terkini -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Edukasi & Wawasan</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                    Tips & Berita Properti Terkini
                </h2>
                <p class="text-sm text-slate-500 mt-1 max-w-xl">
                    Ketahui tips memilih rumah pertama, persiapan syarat pengajuan KPR, dan tren perumahan terkini.
                </p>
            </div>
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 hover:text-brand-700 transition">
                <span>Semua Artikel</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestArticles as $article)
                <article class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col">
                    <a href="{{ route('blog.show', $article->slug) }}" class="h-48 overflow-hidden bg-slate-100 block">
                        <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    </a>
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-2.5 py-1 rounded-md">
                                {{ $article->category ?? 'Properti' }}
                            </span>
                            <h3 class="text-base font-bold text-slate-900 hover:text-brand-600 transition mt-3 line-clamp-2 leading-snug">
                                <a href="{{ route('blog.show', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 mt-2 line-clamp-2">
                                {{ strip_tags($article->content) }}
                            </p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                            <span>{{ $article->published_at ? $article->published_at->format('d M Y') : '' }}</span>
                            <a href="{{ route('blog.show', $article->slug) }}" class="text-brand-600 font-semibold hover:underline">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
