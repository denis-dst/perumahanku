@extends('layouts.app')

@section('title', $project->name . ' - ' . ($project->city ?? 'Perumahan Baru'))
@section('meta_description', $project->seo_description ?? Str::limit($project->description, 160))
@section('meta_image', $project->primaryImage ? $project->primaryImage->url : '')

@section('content')
@php
    $siteName = \App\Models\Setting::get('site_name', 'Perumahanku');
    $waNum = \App\Models\Setting::get('whatsapp_number', '6281234567890');
    $waClean = preg_replace('/[^0-9]/', '', $waNum);
    if (str_starts_with($waClean, '0')) $waClean = '62' . substr($waClean, 1);
@endphp

<div x-data="{
    activeTab: 'tipe-0',
    selectedUnit: null,
    povModalOpen: false,
    lightboxOpen: false,
    lightboxImg: '',
    kprPrice: {{ $project->houseTypes->first() ? $project->houseTypes->first()->price : 450000000 }},
    kprDp: 10,
    kprTenor: 20,
    kprRate: 4.88,
    get kprDpAmount() {
        return (this.kprPrice * this.kprDp) / 100;
    },
    get kprLoan() {
        return this.kprPrice - this.kprDpAmount;
    },
    get kprMonthly() {
        let p = this.kprLoan;
        let r = (this.kprRate / 100) / 12;
        let n = this.kprTenor * 12;
        if (p <= 0 || n <= 0) return 0;
        let c = (p * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
        return Math.round(c);
    },
    formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    },
    openPov(unit) {
        this.selectedUnit = unit;
        this.povModalOpen = true;
    }
}">

    <!-- Flash message modal / notification for booking -->
    @if(session('success'))
        <div class="bg-emerald-600 text-white p-4 shadow-lg sticky top-20 z-50">
            <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-2xl"></i>
                    <div>
                        <p class="font-bold text-sm">{{ session('success') }}</p>
                        <p class="text-xs text-emerald-100">Tim Sales kami siap membantu mengatur jadwal kunjungan Anda.</p>
                    </div>
                </div>
                @if(session('wa_url'))
                    <a href="{{ session('wa_url') }}" target="_blank" class="px-5 py-2.5 rounded-xl bg-white text-emerald-700 font-bold text-xs shadow-md hover:bg-emerald-50 transition flex items-center gap-2">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        <span>Buka WhatsApp Sekarang</span>
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- Project Breadcrumb & Title Section -->
    <section class="bg-white border-b border-slate-200/80 pt-8 pb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-brand-600">Beranda</a>
                <span>/</span>
                <a href="{{ route('projects.index') }}" class="hover:text-brand-600">Katalog</a>
                <span>/</span>
                <a href="{{ route('projects.city', Str::slug($project->city)) }}" class="hover:text-brand-600">{{ $project->city }}</a>
                <span>/</span>
                <span class="text-slate-900 font-medium truncate">{{ $project->name }}</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2.5 mb-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
                            <i class="fa-solid fa-location-dot mr-1"></i> {{ $project->city }}, {{ $project->province }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ $project->available_units_count }} Unit Siap Dipilih
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        {{ $project->name }}
                    </h1>
                    <p class="text-sm text-slate-500 mt-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-map-pin text-brand-500"></i>
                        <span>{{ $project->address }}</span>
                    </p>
                </div>

                <!-- Price Box & Fast Action -->
                <div class="flex flex-wrap items-center gap-4">
                    <div class="text-left lg:text-right">
                        <span class="text-xs text-slate-400 font-medium block">Range Harga Perumahan</span>
                        <span class="text-2xl sm:text-3xl font-extrabold text-nature-600 block">
                            {{ $project->price_range }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="#booking-form" class="px-5 py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md transition flex items-center gap-2">
                            <i class="fa-regular fa-calendar-check"></i>
                            <span>Booking Survei</span>
                        </a>

                        <a href="https://wa.me/{{ $waClean }}?text={{ rawurlencode('Halo Admin ' . $siteName . ', saya ingin tanya unit di perumahan ' . $project->name) }}" target="_blank" class="px-4 py-3 rounded-2xl bg-nature-500 hover:bg-nature-600 text-white font-bold text-sm shadow-md transition flex items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                            <span class="hidden sm:inline">Tanya Sales</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Grid -->
    <section class="bg-slate-900 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 h-[380px] sm:h-[460px]">
                <!-- Main Big Photo -->
                <div class="md:col-span-2 md:row-span-2 relative rounded-2xl overflow-hidden cursor-pointer group"
                     @click="lightboxImg = '{{ $project->primaryImage ? $project->primaryImage->url : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80' }}'; lightboxOpen = true">
                    <img src="{{ $project->primaryImage ? $project->primaryImage->url : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80' }}"
                         alt="{{ $project->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-4">
                        <span class="text-white text-xs font-semibold px-3 py-1 rounded-lg bg-black/40 backdrop-blur-xs flex items-center gap-1.5">
                            <i class="fa-solid fa-expand"></i> Klik untuk memperbesar foto
                        </span>
                    </div>
                </div>

                <!-- Secondary Images -->
                @forelse($project->images->where('is_primary', false)->take(3) as $subImg)
                    <div class="relative rounded-2xl overflow-hidden cursor-pointer group h-full"
                         @click="lightboxImg = '{{ $subImg->url }}'; lightboxOpen = true">
                        <img src="{{ $subImg->url }}" alt="{{ $subImg->caption ?? $project->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @if($subImg->caption)
                            <div class="absolute bottom-2 left-2 right-2 bg-black/60 text-white text-[11px] px-2.5 py-1 rounded-md truncate backdrop-blur-xs">
                                {{ $subImg->caption }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="rounded-2xl overflow-hidden bg-slate-800 flex items-center justify-center text-slate-500 text-xs">
                        Foto Interior
                    </div>
                    <div class="rounded-2xl overflow-hidden bg-slate-800 flex items-center justify-center text-slate-500 text-xs">
                        Foto Lingkungan
                    </div>
                    <div class="rounded-2xl overflow-hidden bg-slate-800 flex items-center justify-center text-slate-500 text-xs">
                        Fasilitas Cluster
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Main Content Tabs & Sidebar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left 8 Cols: Overview, Types, Interactive Siteplan POV, KPR -->
            <div class="lg:col-span-8 space-y-12">

                <!-- 1. Deskripsi & Fasilitas Proyek -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 mb-3">Tentang {{ $project->name }}</h2>
                        <div class="text-sm text-slate-600 leading-relaxed space-y-3">
                            <p>{{ $project->description }}</p>
                        </div>
                    </div>

                    <!-- Facilities Grid -->
                    @if(!empty($project->facilities) && is_array($project->facilities))
                        <div class="pt-6 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-tree-city text-nature-600"></i>
                                <span>Fasilitas Kawasan & Cluster</span>
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($project->facilities as $facility)
                                    <div class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs font-semibold text-slate-700">
                                        <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                                        <span>{{ $facility }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 2. PILIHAN TIPE RUMAH & SPESIFIKASI TEKNIS (Tabs) -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Pilihan Unit</span>
                            <h2 class="text-xl font-bold text-slate-900 mt-0.5">Tipe Rumah & Spesifikasi Material</h2>
                        </div>
                    </div>

                    <!-- Tab Buttons -->
                    <div class="flex flex-wrap gap-2 pb-4 border-b border-slate-100">
                        @foreach($project->houseTypes as $idx => $ht)
                            <button @click="activeTab = 'tipe-{{ $idx }}'; kprPrice = {{ $ht->price }}"
                                    :class="activeTab === 'tipe-{{ $idx }}' ? 'bg-brand-600 text-white font-bold shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium'"
                                    class="px-4 py-2.5 rounded-xl text-xs transition duration-200">
                                {{ $ht->name }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Tab Contents -->
                    <div class="mt-6">
                        @foreach($project->houseTypes as $idx => $ht)
                            <div x-show="activeTab === 'tipe-{{ $idx }}'" x-cloak class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                                    <div class="md:col-span-2 space-y-4">
                                        <div class="flex items-baseline justify-between">
                                            <h3 class="text-2xl font-extrabold text-slate-900">{{ $ht->name }}</h3>
                                            <span class="text-xl font-extrabold text-nature-600">{{ $ht->formatted_price }}</span>
                                        </div>

                                        <p class="text-xs text-slate-600 leading-relaxed">{{ $ht->description }}</p>

                                        <!-- Spec Mini Grid -->
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                                <i class="fa-solid fa-bed text-brand-500 text-lg mb-1 block"></i>
                                                <span class="text-xs font-bold text-slate-800">{{ $ht->bedrooms }} Kamar</span>
                                                <span class="text-[10px] text-slate-400 block">Tidur</span>
                                            </div>
                                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                                <i class="fa-solid fa-bath text-brand-500 text-lg mb-1 block"></i>
                                                <span class="text-xs font-bold text-slate-800">{{ $ht->bathrooms }} Kamar</span>
                                                <span class="text-[10px] text-slate-400 block">Mandi</span>
                                            </div>
                                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                                <i class="fa-solid fa-ruler-combined text-brand-500 text-lg mb-1 block"></i>
                                                <span class="text-xs font-bold text-slate-800">{{ $ht->building_area }} m² / {{ $ht->land_area }} m²</span>
                                                <span class="text-[10px] text-slate-400 block">LB / LT</span>
                                            </div>
                                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                                <i class="fa-solid fa-layer-group text-brand-500 text-lg mb-1 block"></i>
                                                <span class="text-xs font-bold text-slate-800">{{ $ht->floors }} Lantai</span>
                                                <span class="text-[10px] text-slate-400 block">Tingkat</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Floor Plan Denah Thumbnail -->
                                    <div class="bg-slate-50 p-3 rounded-2xl border border-slate-200 text-center">
                                        <p class="text-[11px] font-bold text-slate-700 uppercase mb-2">Denah Ruangan</p>
                                        <div class="h-44 rounded-xl overflow-hidden bg-white cursor-pointer group"
                                             @click="lightboxImg = '{{ $ht->floor_plan_image ?? 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=800&q=80' }}'; lightboxOpen = true">
                                            <img src="{{ $ht->floor_plan_image ?? 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=800&q=80' }}" alt="Denah {{ $ht->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        <span class="text-[10px] text-slate-400 mt-1 block">Klik untuk zoom denah</span>
                                    </div>
                                </div>

                                <!-- Material Specification Table -->
                                @if(!empty($ht->specifications) && is_array($ht->specifications))
                                    <div class="pt-4 border-t border-slate-100">
                                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Spesifikasi Material Bangunan:</h4>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                            @foreach($ht->specifications as $item => $val)
                                                <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                                                    <span class="font-semibold text-slate-600">{{ $item }}</span>
                                                    <span class="text-slate-800 font-medium">{{ $val }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 3. FEATURE KILLER: SITEPLAN INTERAKTIF + POV UNIT MODAL -->
                <div id="siteplan" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md"
                     x-data="{
                         selectedBlockFilter: 'all',
                         hoveredUnit: null
                     }">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-nature-100 text-nature-700 text-xs font-bold mb-1">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                                <span>Fitur Eksklusif Masterplan</span>
                            </div>
                            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">
                                Peta Siteplan Interaktif & POV Unit
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">
                                <strong>Klik arsiran blok kavling</strong> di bawah untuk melihat foto potongan denah serta <em>Point of View (POV)</em> pemandangan nyata unit tersebut!
                            </p>
                        </div>

                        <!-- Legend Status -->
                        <div class="flex items-center gap-3 text-xs font-semibold">
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Tersedia</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-500"></span> Booking</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-rose-500"></span> Terjual</span>
                        </div>
                    </div>

                    <!-- Filter Block Pills -->
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-1">Pilih Blok:</span>
                        <button type="button" @click="selectedBlockFilter = 'all'"
                                :class="selectedBlockFilter === 'all' ? 'bg-brand-600 text-white font-bold shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                class="px-3 py-1 rounded-lg text-xs transition">
                            Semua Blok ({{ $project->units->count() }})
                        </button>
                        @foreach($project->units->pluck('block')->filter()->unique()->sort() as $blk)
                            <button type="button" @click="selectedBlockFilter = '{{ $blk }}'"
                                    :class="selectedBlockFilter === '{{ $blk }}' ? 'bg-brand-600 text-white font-bold shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                    class="px-3 py-1 rounded-lg text-xs transition">
                                Blok {{ $blk }} ({{ $project->units->where('block', $blk)->count() }})
                            </button>
                        @endforeach
                    </div>

                    <!-- Siteplan Canvas Container with SVG Lot Slices -->
                    <div class="relative rounded-2xl overflow-hidden bg-slate-900 border-2 border-slate-200 shadow-inner group aspect-[16/11.37]">
                        <!-- Siteplan Background Map -->
                        <img src="{{ $project->siteplan_image ? asset($project->siteplan_image) : asset('storage/sample-siteplan.jpg') }}"
                             alt="Siteplan {{ $project->name }}"
                             class="w-full h-full object-cover">

                        <!-- SVG Overlay for Sliced Lot Polygons -->
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            @foreach($project->units as $unit)
                                @php
                                    $firstPov = $unit->povImages->first();
                                    $povPhotoUrl = $firstPov ? $firstPov->url : ($unit->images->first() ? $unit->images->first()->url : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80');

                                    $wPct = $unit->width_pct ?: 5.5;
                                    $hPct = $unit->height_pct ?: 6.0;
                                    $xPct = $unit->position_x - ($wPct / 2);
                                    $yPct = $unit->position_y - ($hPct / 2);

                                    $unitPayload = [
                                        'id' => $unit->id,
                                        'block' => $unit->block,
                                        'number' => $unit->number,
                                        'status' => $unit->status,
                                        'status_label' => $unit->status_label,
                                        'status_color' => $unit->status_color,
                                        'facing' => $unit->facing ?? 'Bebas Hambatan',
                                        'type_name' => $unit->houseType ? $unit->houseType->name : 'Standar',
                                        'price_formatted' => $unit->formatted_price,
                                        'land_area' => $unit->land_area ?? ($unit->houseType ? $unit->houseType->land_area : '-'),
                                        'slice_image' => $unit->slice_image_url,
                                        'pov_description' => $unit->pov_description ?? 'Unit menghadap jalan cluster yang asri dan tenang.',
                                        'pov_photo' => $povPhotoUrl,
                                    ];
                                @endphp

                                <g class="cursor-pointer transition-all duration-200"
                                   x-show="selectedBlockFilter === 'all' || selectedBlockFilter === '{{ $unit->block }}'"
                                   @click="openPov({{ json_encode($unitPayload) }})"
                                   @mouseenter="hoveredUnit = {{ json_encode($unitPayload) }}"
                                   @mouseleave="hoveredUnit = null">

                                    <!-- Sliced Unit Lot Shape (Polygon Lines or Rect) -->
                                    @if(!empty($unit->polygon_coords))
                                        <polygon points="{{ $unit->svg_polygon_points }}"
                                                 class="{{ match($unit->status) {
                                                     'available' => 'fill-emerald-500/40 stroke-emerald-400 hover:fill-emerald-500/75',
                                                     'booked' => 'fill-amber-500/40 stroke-amber-400 hover:fill-amber-500/75',
                                                     'sold' => 'fill-rose-500/40 stroke-rose-400 hover:fill-rose-500/75',
                                                     default => 'fill-slate-500/40 stroke-slate-400'
                                                 } }} stroke-[0.4] stroke-linejoin-round transition-all duration-200 hover:stroke-[0.8] hover:stroke-white" />
                                    @else
                                        <rect x="{{ $xPct }}"
                                              y="{{ $yPct }}"
                                              width="{{ $wPct }}"
                                              height="{{ $hPct }}"
                                              rx="0.5"
                                              class="{{ match($unit->status) {
                                                  'available' => 'fill-emerald-500/40 stroke-emerald-400 hover:fill-emerald-500/75',
                                                  'booked' => 'fill-amber-500/40 stroke-amber-400 hover:fill-amber-500/75',
                                                  'sold' => 'fill-rose-500/40 stroke-rose-400 hover:fill-rose-500/75',
                                                  default => 'fill-slate-500/40 stroke-slate-400'
                                              } }} stroke-[0.4] transition-all duration-200 hover:stroke-[0.8] hover:stroke-white" />
                                    @endif

                                    <!-- Center Unit Number Tag -->
                                    <text x="{{ $unit->position_x }}"
                                          y="{{ $unit->position_y + 0.7 }}"
                                          text-anchor="middle"
                                          font-size="1.8"
                                          font-weight="900"
                                          fill="#ffffff"
                                          class="pointer-events-none select-none drop-shadow">
                                        {{ $unit->block }}{{ $unit->number }}
                                    </text>
                                </g>
                            @endforeach
                        </svg>

                        <!-- Instruction & Hover Tooltip -->
                        <div class="absolute bottom-3 left-3 bg-black/75 backdrop-blur-xs text-white text-[11px] font-medium px-3.5 py-1.5 rounded-xl pointer-events-none">
                            <template x-if="!hoveredUnit">
                                <span><i class="fa-solid fa-hand-pointer mr-1 text-emerald-400"></i> Klik salah satu arsiran unit kavling untuk melihat POV</span>
                            </template>
                            <template x-if="hoveredUnit">
                                <span>
                                    <strong x-text="'Kavling ' + hoveredUnit.block + '-' + hoveredUnit.number"></strong>:
                                    <span x-text="hoveredUnit.type_name + ' • ' + hoveredUnit.price_formatted + ' (' + hoveredUnit.status_label + ')'"></span>
                                </span>
                            </template>
                        </div>
                    </div>

                    <!-- Unit Quick List Table -->
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Daftar Kavling & Status:</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5">
                            @foreach($project->units as $u)
                                @php
                                    $firstPov = $u->povImages->first();
                                    $povPhotoUrl = $firstPov ? $firstPov->url : ($u->images->first() ? $u->images->first()->url : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80');
                                    $uPayload = [
                                        'id' => $u->id,
                                        'block' => $u->block,
                                        'number' => $u->number,
                                        'status' => $u->status,
                                        'status_label' => $u->status_label,
                                        'status_color' => $u->status_color,
                                        'facing' => $u->facing ?? 'Bebas Hambatan',
                                        'type_name' => $u->houseType ? $u->houseType->name : 'Standar',
                                        'price_formatted' => $u->formatted_price,
                                        'land_area' => $u->land_area ?? ($u->houseType ? $u->houseType->land_area : '-'),
                                        'pov_description' => $u->pov_description ?? 'Unit dengan pemandangan terbuka yang nyaman.',
                                        'pov_photo' => $povPhotoUrl,
                                    ];
                                @endphp
                                <div @click="openPov({{ json_encode($uPayload) }})"
                                     class="p-2.5 rounded-xl border border-slate-200 hover:border-brand-500 hover:bg-brand-50/50 cursor-pointer transition text-left flex items-center justify-between">
                                    <div>
                                        <span class="text-xs font-extrabold text-slate-900 block">Kav. {{ $u->block }}-{{ $u->number }}</span>
                                        <span class="text-[10px] text-slate-400 block">{{ $u->houseType ? $u->houseType->name : '' }}</span>
                                    </div>
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full {{ $u->status_color }}">
                                        {{ $u->status_label }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 4. KALKULATOR SIMULASI KPR INTERAKTIF UNTUK PROYEK INI -->
                <div class="bg-gradient-to-br from-brand-50 to-nature-50 rounded-3xl p-6 sm:p-8 border border-brand-100">
                    <div class="max-w-xl mb-6">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-700">Rencana Angsuran</span>
                        <h2 class="text-xl font-bold text-slate-900 mt-0.5">Simulasi KPR {{ $project->name }}</h2>
                        <p class="text-xs text-slate-500 mt-1">Ubah harga atau persentase DP untuk melihat estimasi cicilan per bulan.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center bg-white p-6 rounded-2xl border border-brand-200/60 shadow-xs">
                        <!-- Inputs -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold uppercase text-slate-600">Plafon Harga Rumah</label>
                                <div class="text-lg font-black text-brand-600 mt-1" x-text="formatRupiah(kprPrice)"></div>
                            </div>

                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-600 mb-1">
                                    <span>Uang Muka (DP: <span x-text="kprDp"></span>%)</span>
                                    <span class="text-slate-800" x-text="formatRupiah(kprDpAmount)"></span>
                                </div>
                                <input type="range" min="0" max="50" step="5" x-model.number="kprDp" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-nature-500">
                            </div>

                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-600 mb-1">
                                    <span>Tenor Pinjaman</span>
                                    <span class="text-slate-800"><span x-text="kprTenor"></span> Tahun</span>
                                </div>
                                <input type="range" min="5" max="30" step="5" x-model.number="kprTenor" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-brand-600">
                            </div>

                            <div>
                                <label class="text-xs font-bold uppercase text-slate-600 block mb-1">Pilih Bank Rekanan</label>
                                <select @change="kprRate = parseFloat($event.target.value)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
                                    @foreach($banks as $b)
                                        <option value="{{ $b->interest_rate }}">{{ $b->bank_name }} (Bunga {{ $b->interest_rate }}%)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Result -->
                        <div class="text-center p-6 rounded-2xl bg-brand-50/70 border border-brand-100 flex flex-col justify-center">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Estimasi Cicilan</span>
                            <div class="text-3xl font-black text-brand-700 my-2" x-text="formatRupiah(kprMonthly)">Rp 0</div>
                            <span class="text-xs text-slate-500 mb-4">per bulan selama <span x-text="kprTenor"></span> tahun</span>

                            <a :href="'https://wa.me/{{ $waClean }}?text=' + encodeURIComponent('Halo Sales {{ $siteName }}, saya ingin konsultasi KPR untuk {{ $project->name }} dengan simulasi harga ' + formatRupiah(kprPrice) + ' DP ' + kprDp + '% tenor ' + kprTenor + ' tahun.')"
                               target="_blank"
                               class="py-2.5 px-4 rounded-xl bg-nature-500 hover:bg-nature-600 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                                <i class="fa-brands fa-whatsapp text-base"></i>
                                <span>Konsultasi Syarat KPR via WA</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right 4 Cols: Sticky Form Booking & Contact -->
            <div class="lg:col-span-4">
                <div id="booking-form" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-lg sticky top-28 space-y-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-100 text-brand-700 text-xs font-bold mb-2">
                            <i class="fa-regular fa-calendar-check"></i>
                            <span>Layanan Bebas Biaya</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900">Jadwalkan Survei Lokasi</h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Pilih jadwal kunjungan Anda. Tim marketing kami akan menemani dan menjelaskan detail rumah di lokasi.
                        </p>
                    </div>

                    <form action="{{ route('booking.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">

                        <!-- Tipe Rumah -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Tipe Rumah Diminati</label>
                            <select name="house_type_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                                <option value="">Semua Tipe / Belum Tahu</option>
                                @foreach($project->houseTypes as $ht)
                                    <option value="{{ $ht->id }}">{{ $ht->name }} - {{ $ht->formatted_price }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Nama Lengkap *</label>
                            <input type="text" name="name" required placeholder="Contoh: Budi Pratama" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>

                        <!-- No WhatsApp -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Nomor WhatsApp Aktif *</label>
                            <input type="tel" name="phone" required placeholder="Contoh: 08123456789" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>

                        <!-- Rencana Tanggal & Waktu -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Tanggal *</label>
                                <input type="date" name="preferred_date" min="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Waktu *</label>
                                <select name="preferred_time" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-2 py-2.5 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                                    <option value="10:00 WIB (Pagi)">10:00 (Pagi)</option>
                                    <option value="13:00 WIB (Siang)">13:00 (Siang)</option>
                                    <option value="15:30 WIB (Sore)">15:30 (Sore)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Catatan Tambahan</label>
                            <textarea name="message" rows="2" placeholder="Contoh: Mau sekalian konsultasi berkas KPR BTN..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-2xl bg-gradient-to-r from-brand-600 to-nature-600 hover:from-brand-700 hover:to-nature-700 text-white font-bold text-sm shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fa-regular fa-paper-plane"></i>
                            <span>Kirim Jadwal Survei</span>
                        </button>
                    </form>

                    <!-- Fast Contact Card -->
                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <p class="text-xs text-slate-400 text-center">Butuh respon cepat tanpa mengisi form?</p>
                        <a href="https://wa.me/{{ $waClean }}?text={{ rawurlencode('Halo Sales ' . $siteName . ', saya ingin konsultasi langsung untuk proyek ' . $project->name) }}"
                           target="_blank"
                           class="w-full py-2.5 rounded-xl border border-nature-300 bg-nature-50 hover:bg-nature-100 text-nature-800 font-bold text-xs transition flex items-center justify-center gap-2">
                            <i class="fa-brands fa-whatsapp text-nature-600 text-lg"></i>
                            <span>Chat Langsung via WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- POV UNIT MODAL (Interactive Popup when siteplan pin is clicked) -->
    <div x-show="povModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="povModalOpen = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200"
                 @click.away="povModalOpen = false">

                <template x-if="selectedUnit">
                    <div>
                        <!-- Header with Status -->
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-full bg-brand-500 text-white font-extrabold text-xs flex items-center justify-center">
                                    <span x-text="selectedUnit.block + selectedUnit.number"></span>
                                </span>
                                <div>
                                    <h3 class="font-extrabold text-slate-900 text-base" x-text="'Kavling ' + selectedUnit.block + '-' + selectedUnit.number"></h3>
                                    <p class="text-xs text-slate-400" x-text="selectedUnit.type_name + ' • Luas Tanah: ' + selectedUnit.land_area + ' m²'"></p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold px-3 py-1 rounded-full" :class="selectedUnit.status_color" x-text="selectedUnit.status_label"></span>
                                <button @click="povModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1">
                                    <i class="fa-solid fa-xmark text-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Media Showcase: Real POV Photo + Sliced Lot Blueprint -->
                        <div class="grid grid-cols-1 md:grid-cols-12 bg-slate-950">
                            <!-- Left: Real Photo POV (8 cols) -->
                            <div class="md:col-span-8 relative h-64 sm:h-80 bg-slate-900 overflow-hidden">
                                <img :src="selectedUnit.pov_photo" :alt="'POV Kavling ' + selectedUnit.block + '-' + selectedUnit.number" class="w-full h-full object-cover">
                                <!-- Overlay Badge POV -->
                                <div class="absolute bottom-3 left-3 bg-black/80 backdrop-blur-xs text-white px-3.5 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-2">
                                    <i class="fa-solid fa-street-view text-emerald-400 text-sm"></i>
                                    <span>Sudut Pandang (POV) Nyata dari Teras Kavling</span>
                                </div>
                            </div>

                            <!-- Right: Sliced Lot Blueprint Cutout (4 cols) -->
                            <div class="md:col-span-4 p-4 bg-slate-900 flex flex-col justify-center items-center text-center border-t md:border-t-0 md:border-l border-slate-800">
                                <span class="text-[10px] uppercase font-bold text-brand-400 tracking-wider mb-2 block">
                                    <i class="fa-solid fa-crop-simple mr-1"></i> Potongan Arsiran Siteplan
                                </span>
                                <div class="w-28 h-28 rounded-2xl overflow-hidden bg-slate-950 border border-slate-700 shadow-inner flex items-center justify-center relative">
                                    <template x-if="selectedUnit.slice_image">
                                        <img :src="selectedUnit.slice_image" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!selectedUnit.slice_image">
                                        <div class="text-[10px] text-slate-500 font-bold p-2">Kavling <span x-text="selectedUnit.block + selectedUnit.number"></span></div>
                                    </template>
                                </div>
                                <span class="text-[10px] text-slate-400 mt-2">Posisi tepat di masterplan</span>
                            </div>
                        </div>

                        <!-- POV Description & Details -->
                        <div class="p-6 space-y-4">
                            <div class="p-4 rounded-2xl bg-brand-50/70 border border-brand-100 text-xs text-slate-700 leading-relaxed">
                                <div class="flex items-center gap-2 font-bold text-brand-800 mb-1">
                                    <i class="fa-solid fa-compass text-brand-600"></i>
                                    <span>Arah Hadap: <span x-text="selectedUnit.facing"></span></span>
                                </div>
                                <p x-text="selectedUnit.pov_description"></p>
                            </div>

                            <div class="flex items-center justify-between pt-2">
                                <div>
                                    <span class="text-[11px] text-slate-400 uppercase font-semibold block">Harga Unit Kavling</span>
                                    <span class="text-xl font-extrabold text-nature-600" x-text="selectedUnit.price_formatted"></span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a :href="'https://wa.me/{{ $waClean }}?text=' + encodeURIComponent('Halo Sales {{ $siteName }}, saya sangat tertarik dengan Kavling ' + selectedUnit.block + '-' + selectedUnit.number + ' di {{ $project->name }}. Apakah unit ini masih tersedia?')"
                                       target="_blank"
                                       class="px-4 py-2.5 rounded-xl bg-nature-500 hover:bg-nature-600 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                                        <i class="fa-brands fa-whatsapp text-base"></i>
                                        <span>Tanya Unit Ini</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Lightbox Zoom Modal for Images -->
    <div x-show="lightboxOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md" @click.self="lightboxOpen = false">
        <button @click="lightboxOpen = false" class="absolute top-6 right-6 text-white text-2xl hover:text-slate-300">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <img :src="lightboxImg" alt="Enlarged photo" class="max-w-full max-h-[90vh] rounded-2xl shadow-2xl object-contain">
    </div>

</div>
@endsection
