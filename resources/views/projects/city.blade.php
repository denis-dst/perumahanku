@extends('layouts.app')

@section('title', 'Perumahan Baru di ' . $cityFormatted . ' - Pilihan Rumah Murah & Strategis')
@section('meta_description', 'Cari perumahan baru di ' . $cityFormatted . '? Temukan daftar perumahan subsidi dan komersil cicilan ringan, lokasi dekat tol dan stasiun, legalitas SHM terjamin.')

@section('content')
<!-- Header Banner SEO -->
<section class="bg-gradient-to-r from-brand-600 via-brand-500 to-nature-600 text-white py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 text-xs text-brand-100 mb-3">
            <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
            <span>/</span>
            <a href="{{ route('projects.index') }}" class="hover:underline">Perumahan</a>
            <span>/</span>
            <span class="text-white font-bold">{{ $cityFormatted }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
            Perumahan Baru Terdekat di {{ $cityFormatted }}
        </h1>
        <p class="text-sm text-brand-100 mt-2 max-w-2xl leading-relaxed">
            Daftar perumahan baru pilihan terbaik di wilayah {{ $cityFormatted }} dan sekitarnya. Fasilitas one gate system, dekat akses transportasi, dan proses KPR dibantu sampai serah terima kunci.
        </p>
    </div>
</section>

<!-- Content -->
<section class="py-12 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- City Selector Toolbar (Clean, Responsive, No Clutter) -->
        <div class="bg-white rounded-2xl p-2.5 sm:p-3 border border-slate-200/90 shadow-2xs mb-8"
             x-data="{
                 scrollLeft() { $refs.chipTrack.scrollBy({ left: -220, behavior: 'smooth' }) },
                 scrollRight() { $refs.chipTrack.scrollBy({ left: 220, behavior: 'smooth' }) }
             }"
             x-init="$nextTick(() => {
                 const activeChip = $refs.chipTrack.querySelector('[data-active=true]');
                 if (activeChip) {
                     activeChip.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                 }
             })">
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Dropdown Quick Switcher -->
                <div class="flex items-center gap-2 pl-2 pr-3 border-r border-slate-200 shrink-0">
                    <span class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </span>
                    <span class="text-xs font-bold text-slate-800 hidden sm:inline whitespace-nowrap">Wilayah:</span>

                    <div class="relative">
                        <select onchange="if(this.value) window.location.href=this.value" aria-label="Pilih Kabupaten atau Kota di Provinsi Lampung" class="appearance-none bg-slate-100 hover:bg-slate-200/80 border border-slate-200 text-slate-800 text-xs font-semibold py-1.5 pl-3 pr-7 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 cursor-pointer transition">
                            @foreach($cities as $c)
                                @php
                                    $isActive = Str::slug($c) === request()->route('city') || strtolower($c) === strtolower($cityFormatted) || str_contains(strtolower($cityFormatted), strtolower(str_replace(['kabupaten ', 'kota '], '', $c)));
                                @endphp
                                <option value="{{ route('projects.city', Str::slug($c)) }}" {{ $isActive ? 'selected' : '' }}>
                                    {{ $c }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-slate-500 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Nav Button Left (Desktop) -->
                <button type="button" @click="scrollLeft()" aria-label="Geser pilihan wilayah ke kiri" class="hidden md:flex w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-800 items-center justify-center text-xs shrink-0 transition">
                    <i class="fa-solid fa-chevron-left text-[11px]"></i>
                </button>

                <!-- Horizontal Scrollable Chip Track (Single Neat Line, No Wrapping) -->
                <div x-ref="chipTrack" class="flex-1 flex items-center gap-1.5 overflow-x-auto py-0.5" style="scrollbar-width: none; -ms-overflow-style: none;">
                    @foreach($cities as $c)
                        @php
                            $isActive = Str::slug($c) === request()->route('city') || strtolower($c) === strtolower($cityFormatted) || str_contains(strtolower($cityFormatted), strtolower(str_replace(['kabupaten ', 'kota '], '', $c)));
                            $shortName = str_replace(['Kabupaten ', 'Kota '], '', $c);
                            $isKota = str_starts_with($c, 'Kota');
                        @endphp
                        <a href="{{ route('projects.city', Str::slug($c)) }}"
                           data-active="{{ $isActive ? 'true' : 'false' }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 shrink-0 {{ $isActive ? 'bg-brand-600 text-white shadow-xs font-bold scale-[1.02]' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200/80' }}">
                            <span class="text-[10px] {{ $isActive ? 'text-brand-100 font-medium' : 'text-slate-600 font-normal' }}">
                                {{ $isKota ? 'Kota' : 'Kab.' }}
                            </span>
                            <span>{{ $shortName }}</span>
                        </a>
                    @endforeach
                </div>

                <!-- Nav Button Right (Desktop) -->
                <button type="button" @click="scrollRight()" aria-label="Geser pilihan wilayah ke kanan" class="hidden md:flex w-7 h-7 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-800 items-center justify-center text-xs shrink-0 transition">
                    <i class="fa-solid fa-chevron-right text-[11px]"></i>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($projects as $p)
                <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="relative h-60 bg-slate-100 overflow-hidden">
                            <img src="{{ $p->primaryImage ? $p->primaryImage->url : asset('images/default-project.jpg') }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-3 left-3">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/95 text-brand-700 shadow-sm">
                                    {{ $p->city }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition">
                                <a href="{{ route('projects.show', $p->slug) }}">{{ $p->name }}</a>
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">{{ $p->address }}</p>
                            <p class="text-xs text-slate-600 mt-3 line-clamp-2 leading-relaxed">
                                {{ $p->tagline ?? Str::limit($p->description, 110) }}
                            </p>
                        </div>
                    </div>

                    <div class="p-6 pt-0 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-slate-500 uppercase font-semibold block">Harga Mulai</span>
                            <span class="text-sm font-extrabold text-nature-600">{{ $p->price_range }}</span>
                        </div>
                        <a href="{{ route('projects.show', $p->slug) }}" class="px-4 py-2 rounded-xl bg-brand-50 group-hover:bg-brand-600 text-brand-700 group-hover:text-white font-semibold text-xs transition">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-slate-200 p-8">
                    <p class="text-sm text-slate-500">Belum ada proyek perumahan di kota ini saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
