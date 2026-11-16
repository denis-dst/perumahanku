@extends('layouts.app')

@section('title', 'Katalog Perumahan Baru - Temukan Rumah Idaman Anda')
@section('meta_description', 'Jelajahi seluruh perumahan baru dengan fasilitas lengkap, cicilan KPR terjangkau, legalitas SHM, dan booking survei lokasi langsung.')

@section('content')
<!-- Header Banner -->
<section class="bg-gradient-to-r from-brand-600 via-brand-500 to-nature-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold tracking-tight">Katalog Perumahan Pilihan</h1>
        <p class="text-sm text-brand-100 mt-2 max-w-2xl">
            Temukan hunian baru berkualitas dari developer terpercaya. Filter berdasarkan wilayah, budget cicilan KPR, dan tipe kamar yang sesuai untuk Anda.
        </p>
    </div>
</section>

<!-- Main Catalog Layout -->
<section class="py-12 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Filter -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs sticky top-28 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <h2 class="font-bold text-slate-900 text-base flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-brand-500"></i>
                            <span>Filter Properti</span>
                        </h2>
                        <a href="{{ route('projects.index') }}" class="text-xs text-brand-600 hover:underline">Reset</a>
                    </div>

                    <form action="{{ route('projects.index') }}" method="GET" class="space-y-5">
                        <!-- Keyword -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1.5">Kata Kunci</label>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama perumahan..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>

                        <!-- Kota / Lokasi -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1.5">Kota / Wilayah</label>
                            <select name="city" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                                <option value="">Semua Wilayah</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c }}" {{ request('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Range Harga Maksimal -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1.5">Budget Maksimal</label>
                            <select name="max_price" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                                <option value="">Semua Harga</option>
                                <option value="200000000" {{ request('max_price') == '200000000' ? 'selected' : '' }}>Hingga Rp 200 Juta</option>
                                <option value="400000000" {{ request('max_price') == '400000000' ? 'selected' : '' }}>Hingga Rp 400 Juta</option>
                                <option value="600000000" {{ request('max_price') == '600000000' ? 'selected' : '' }}>Hingga Rp 600 Juta</option>
                                <option value="1000000000" {{ request('max_price') == '1000000000' ? 'selected' : '' }}>Hingga Rp 1 Miliar</option>
                                <option value="2000000000" {{ request('max_price') == '2000000000' ? 'selected' : '' }}>Hingga Rp 2 Miliar</option>
                            </select>
                        </div>

                        <!-- Urutan -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1.5">Urutkan</label>
                            <select name="sort" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru Diluncurkan</option>
                                <option value="terpopuler" {{ request('sort') == 'terpopuler' ? 'selected' : '' }}>Paling Direkomendasikan</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md transition">
                            Terapkan Filter
                        </button>
                    </form>
                </div>
            </div>

            <!-- Projects Grid Content -->
            <div class="lg:col-span-3">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-sm text-slate-500">
                        Menampilkan <span class="font-bold text-slate-800">{{ $projects->total() }}</span> perumahan
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($projects as $p)
                        <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                            <div>
                                <!-- Image with Badge -->
                                <div class="relative h-56 bg-slate-100 overflow-hidden">
                                    @php
                                        $imgUrl = $p->primaryImage ? $p->primaryImage->url : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80';
                                    @endphp
                                    <img src="{{ $imgUrl }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                    <div class="absolute top-3 left-3 flex flex-col gap-1 items-start">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/95 text-brand-700 shadow-sm backdrop-blur-xs flex items-center gap-1.5">
                                            <i class="fa-solid fa-location-dot text-brand-500"></i> {{ $p->city }}
                                        </span>
                                    </div>

                                    <div class="absolute bottom-3 right-3">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-900/80 text-white backdrop-blur-xs">
                                            {{ $p->available_units_count }} Unit Sisa
                                        </span>
                                    </div>
                                </div>

                                <!-- Body -->
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition">
                                        <a href="{{ route('projects.show', $p->slug) }}">
                                            {{ $p->name }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-map-pin"></i> {{ $p->district ? $p->district . ', ' : '' }}{{ $p->city }}
                                    </p>

                                    <p class="text-xs text-slate-600 mt-3 line-clamp-2 leading-relaxed">
                                        {{ $p->tagline ?? Str::limit($p->description, 100) }}
                                    </p>

                                    <!-- Types Badges -->
                                    <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap gap-1.5">
                                        @foreach($p->houseTypes as $ht)
                                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[11px] font-medium">
                                                {{ $ht->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Price & CTA -->
                            <div class="px-6 pb-6 pt-2 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Harga Mulai</span>
                                    <span class="text-sm font-extrabold text-nature-600">
                                        {{ $p->price_range }}
                                    </span>
                                </div>
                                <a href="{{ route('projects.show', $p->slug) }}" class="px-4 py-2 rounded-xl bg-brand-50 group-hover:bg-brand-600 text-brand-700 group-hover:text-white font-semibold text-xs transition duration-300">
                                    Lihat Detail →
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-16 bg-white rounded-3xl border border-slate-200 p-8">
                            <i class="fa-solid fa-house-chimney-crack text-4xl text-slate-300 mb-3 block"></i>
                            <h3 class="text-base font-bold text-slate-700">Tidak ada perumahan yang cocok</h3>
                            <p class="text-xs text-slate-500 mt-1 mb-4">Coba sesuaikan filter pencarian atau pilih kota lainnya.</p>
                            <a href="{{ route('projects.index') }}" class="px-4 py-2 rounded-xl bg-brand-600 text-white font-semibold text-xs inline-block">
                                Reset Filter
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
