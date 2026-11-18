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
        <!-- City Pills -->
        <div class="flex flex-wrap items-center gap-2 mb-8">
            <span class="text-xs text-slate-600 font-bold uppercase mr-2">Pilih Kota:</span>
            @foreach($cities as $c)
                <a href="{{ route('projects.city', Str::slug($c)) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ strtolower($c) == strtolower($cityFormatted) ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                    {{ $c }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($projects as $p)
                <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="relative h-60 bg-slate-100 overflow-hidden">
                            <img src="{{ $p->primaryImage ? $p->primaryImage->url : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
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
