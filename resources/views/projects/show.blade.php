@extends('layouts.app')

@section('title', $project->name . ' - ' . ($project->city ?? 'Perumahan Baru'))
@section('meta_description', $project->seo_description ?? Str::limit($project->description, 160))
@section('meta_image', $project->primaryImage ? $project->primaryImage->url : '')

@section('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "SingleFamilyResidence",
  "name": "{{ $project->name }}",
  "description": "{{ $project->seo_description ?? Str::limit($project->description, 160) }}",
  "url": "{{ url()->current() }}",
  "image": "{{ $project->primaryImage ? $project->primaryImage->url : asset('images/logo.png') }}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ $project->address }}",
    "addressLocality": "{{ $project->district ?? $project->city }}",
    "addressRegion": "{{ $project->province ?? 'Lampung' }}",
    "addressCountry": "ID"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": {{ $project->latitude ?? -5.39714 }},
    "longitude": {{ $project->longitude ?? 105.26679 }}
  }
}
</script>
@endsection

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
    unitModalTab: 'foto',
    selectedBlockFilter: 'all',
    hoveredUnit: null,
    lightboxOpen: false,
    lightboxImg: '',
    floorPlanModalOpen: false,
    floorPlanTitle: '',
    floorPlanSubtitle: '',
    floorPlanSlides: [],
    currentSlideIdx: 0,
    zoomLevel: 1.0,
    panOffset: { x: 0, y: 0 },
    isPanning: false,
    panStart: { x: 0, y: 0 },

    openFloorPlanModal(title, subtitle, slides, initialIdx = 0) {
        this.floorPlanTitle = title;
        this.floorPlanSubtitle = subtitle;
        this.floorPlanSlides = slides || [];
        this.currentSlideIdx = initialIdx;
        this.zoomLevel = 1.0;
        this.panOffset = { x: 0, y: 0 };
        this.floorPlanModalOpen = true;
    },

    nextSlide() {
        if (this.floorPlanSlides.length > 0) {
            this.currentSlideIdx = (this.currentSlideIdx + 1) % this.floorPlanSlides.length;
            this.zoomLevel = 1.0;
            this.panOffset = { x: 0, y: 0 };
        }
    },

    prevSlide() {
        if (this.floorPlanSlides.length > 0) {
            this.currentSlideIdx = (this.currentSlideIdx - 1 + this.floorPlanSlides.length) % this.floorPlanSlides.length;
            this.zoomLevel = 1.0;
            this.panOffset = { x: 0, y: 0 };
        }
    },

    goToSlide(idx) {
        this.currentSlideIdx = idx;
        this.zoomLevel = 1.0;
        this.panOffset = { x: 0, y: 0 };
    },

    zoomIn() {
        if (this.zoomLevel < 3.0) {
            this.zoomLevel = parseFloat((this.zoomLevel + 0.25).toFixed(2));
        }
    },

    zoomOut() {
        if (this.zoomLevel > 0.75) {
            this.zoomLevel = parseFloat((this.zoomLevel - 0.25).toFixed(2));
        }
    },

    resetZoom() {
        this.zoomLevel = 1.0;
        this.panOffset = { x: 0, y: 0 };
    },

    startPan(e) {
        if (this.zoomLevel > 1.0) {
            this.isPanning = true;
            this.panStart = { x: e.clientX - this.panOffset.x, y: e.clientY - this.panOffset.y };
        }
    },

    onPan(e) {
        if (this.isPanning && this.zoomLevel > 1.0) {
            this.panOffset.x = e.clientX - this.panStart.x;
            this.panOffset.y = e.clientY - this.panStart.y;
        }
    },

    endPan() {
        this.isPanning = false;
    },

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
        this.unitModalTab = 'foto';
        this.povModalOpen = true;
    },
    calculateUnitKpr(price) {
        if (price > 0) {
            this.kprPrice = price;
        }
        this.povModalOpen = false;
        const target = document.getElementById('kpr-calculator');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
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
                        <span class="text-xs text-slate-500 font-semibold block">Range Harga Perumahan</span>
                        <span class="text-2xl sm:text-3xl font-extrabold text-nature-600 block">
                            {{ $project->price_range }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="#booking-form" class="px-5 py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md transition flex items-center gap-2">
                            <i class="fa-regular fa-calendar-check"></i>
                            <span>Booking Survei</span>
                        </a>

                        <a href="https://wa.me/{{ $waClean }}?text={{ rawurlencode('Halo Admin ' . $siteName . ', saya ingin tanya unit di perumahan ' . $project->name) }}" target="_blank" class="px-4 py-3 rounded-2xl bg-nature-600 hover:bg-nature-700 text-white font-bold text-sm shadow-md transition flex items-center gap-2">
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
                <!-- Main Big Photo (LCP Optimized) -->
                <div class="md:col-span-2 md:row-span-2 relative rounded-2xl overflow-hidden cursor-pointer group"
                     @click="lightboxImg = '{{ $project->primaryImage ? $project->primaryImage->url : asset('images/default-project.jpg') }}'; lightboxOpen = true">
                    <img src="{{ $project->primaryImage ? $project->primaryImage->url : asset('images/default-project.jpg') }}"
                         alt="{{ $project->name }}"
                         fetchpriority="high"
                         decoding="async"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-4">
                        <span class="text-white text-xs font-semibold px-3 py-1 rounded-lg bg-black/40 backdrop-blur-xs flex items-center gap-1.5">
                            <i class="fa-solid fa-expand"></i> Klik untuk memperbesar foto
                        </span>
                    </div>
                </div>

                <!-- Secondary Images (Lazy Loaded) -->
                @forelse($project->images->where('is_primary', false)->take(3) as $subImg)
                    <div class="relative rounded-2xl overflow-hidden cursor-pointer group h-full"
                         @click="lightboxImg = '{{ $subImg->url }}'; lightboxOpen = true">
                        <img src="{{ $subImg->url }}"
                             alt="{{ $subImg->caption ?? $project->name }}"
                             loading="lazy"
                             decoding="async"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
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
                            <button type="button"
                                    @click="activeTab = 'tipe-{{ $idx }}'; kprPrice = {{ $ht->price }}"
                                    :aria-label="'Pilih tipe rumah ' + '{{ $ht->name }}'"
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
                                                <span class="text-[10px] text-slate-500 font-medium block">Tidur</span>
                                            </div>
                                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                                <i class="fa-solid fa-bath text-brand-500 text-lg mb-1 block"></i>
                                                <span class="text-xs font-bold text-slate-800">{{ $ht->bathrooms }} Kamar</span>
                                                <span class="text-[10px] text-slate-500 font-medium block">Mandi</span>
                                            </div>
                                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                                <i class="fa-solid fa-ruler-combined text-brand-500 text-lg mb-1 block"></i>
                                                <span class="text-xs font-bold text-slate-800">{{ $ht->building_area }} m² / {{ $ht->land_area }} m²</span>
                                                <span class="text-[10px] text-slate-500 font-medium block">LB / LT</span>
                                            </div>
                                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                                                <i class="fa-solid fa-layer-group text-brand-500 text-lg mb-1 block"></i>
                                                <span class="text-xs font-bold text-slate-800">{{ $ht->floors }} Lantai</span>
                                                <span class="text-[10px] text-slate-500 font-medium block">Tingkat</span>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $floorPlanSlides = [];

                                        // Slide 1: Denah Tata Ruang 2D
                                        $mainPlanImg = $ht->floor_plan_image_url ?? asset('images/default-floorplan.jpg');
                                        $floorPlanSlides[] = [
                                            'title' => 'Denah Tata Ruang Arsitektur (2D)',
                                            'caption' => 'Ukuran & dimensi kamar, pembagian ruang tamu, dapur, carport dan sirkulasi',
                                            'badge' => 'Denah Utama',
                                            'url' => $mainPlanImg,
                                        ];

                                        // Slide 2: Denah Lantai 2 / 3D Isometric View
                                        if ($ht->floors > 1) {
                                            $floorPlanSlides[] = [
                                                'title' => 'Denah Ruang Lantai 2 (Area Privat)',
                                                'caption' => 'Kamar tidur anak/tamu, kamar mandi atas, void tangga dan balkon',
                                                'badge' => 'Lantai 2',
                                                'url' => asset('uploads/siteplans/siteplan-grand-harmony.jpg'),
                                            ];
                                        } else {
                                            $floorPlanSlides[] = [
                                                'title' => 'Visualisasi 3D Tata Letak Ruangan',
                                                'caption' => 'Konsep penataan furniture, ventilasi silang & pencahayaan alami',
                                                'badge' => '3D Layout',
                                                'url' => asset('uploads/projects/living-room.jpg'),
                                            ];
                                        }

                                        // Slide 3 & 4: Galeri Foto Interior / Fasad Unit
                                        if ($ht->images && $ht->images->isNotEmpty()) {
                                            foreach ($ht->images as $img) {
                                                $floorPlanSlides[] = [
                                                    'title' => $img->caption ?: 'Detail Interior ' . $ht->name,
                                                    'caption' => 'Spesifikasi visual nyata ' . $ht->name,
                                                    'badge' => 'Foto Unit',
                                                    'url' => $img->url,
                                                ];
                                            }
                                        } else {
                                            $floorPlanSlides[] = [
                                                'title' => 'Ruang Keluarga & Ruang Tamu Sejuk',
                                                'caption' => 'Langit-langit tinggi menghadirkan kesan luas dan sirkulasi udara optimal',
                                                'badge' => 'Interior',
                                                'url' => asset('uploads/projects/project-grand-harmony.jpg'),
                                            ];
                                            $floorPlanSlides[] = [
                                                'title' => 'Kamar Tidur Utama Nyaman',
                                                'caption' => 'Pencahayaan jendela maksimal menghadap taman cluster asri',
                                                'badge' => 'Kamar Tidur',
                                                'url' => asset('uploads/projects/master-bedroom.jpg'),
                                            ];
                                        }
                                    @endphp

                                    <!-- Floor Plan Denah Thumbnail Card with Slide Counter & Interaction -->
                                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200 text-center flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-[11px] font-bold text-slate-700 uppercase">Denah & Ruangan</span>
                                                <span class="text-[10px] text-brand-700 font-extrabold bg-brand-100/80 px-2 py-0.5 rounded-full flex items-center gap-1 shadow-2xs">
                                                    <i class="fa-solid fa-images text-[9px]"></i> {{ count($floorPlanSlides) }} Slide
                                                </span>
                                            </div>

                                            <div class="h-44 rounded-xl overflow-hidden bg-white cursor-pointer group relative shadow-2xs border border-slate-100"
                                                 @click="openFloorPlanModal('{{ $ht->name }}', '{{ $ht->building_area }} m² / {{ $ht->land_area }} m² • {{ $ht->bedrooms }} Kamar Tidur • {{ $ht->bathrooms }} Kamar Mandi', {{ json_encode($floorPlanSlides) }}, 0)">
                                                <img src="{{ $mainPlanImg }}" alt="Denah {{ $ht->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white backdrop-blur-[2px]">
                                                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-lg mb-1 shadow-lg group-hover:scale-110 transition-transform">
                                                        <i class="fa-solid fa-expand"></i>
                                                    </div>
                                                    <span class="text-xs font-bold drop-shadow">Klik untuk Zoom & Slide</span>
                                                    <span class="text-[10px] text-white/80 mt-0.5 drop-shadow">Bisa di-slide detail ruangan</span>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button"
                                                @click="openFloorPlanModal('{{ $ht->name }}', '{{ $ht->building_area }} m² / {{ $ht->land_area }} m² • {{ $ht->bedrooms }} Kamar Tidur • {{ $ht->bathrooms }} Kamar Mandi', {{ json_encode($floorPlanSlides) }}, 0)"
                                                aria-label="Buka denah dan detail ruangan tipe {{ $ht->name }}"
                                                class="mt-2.5 w-full py-2 px-3 rounded-xl bg-white hover:bg-brand-50 border border-slate-200 hover:border-brand-300 text-brand-600 font-bold text-xs transition flex items-center justify-center gap-1.5 shadow-2xs group/btn">
                                            <i class="fa-solid fa-magnifying-glass-plus text-xs group-hover/btn:scale-110 transition-transform"></i>
                                            <span>Buka & Slide Detail Denah</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Modern & Friendly Material Specifications Grid -->
                                @if(!empty($ht->specifications) && is_array($ht->specifications))
                                    <div class="pt-6 border-t border-slate-100">
                                        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-xl bg-nature-100 text-nature-700 flex items-center justify-center text-xs shadow-2xs">
                                                    <i class="fa-solid fa-shield-halved"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">Kualitas & Spesifikasi Teknis Material</h4>
                                                    <p class="text-[11px] text-slate-500 font-medium">Standar mutu bangunan kokoh, rapi, dan bergaransi resmi</p>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-2.5 py-1 rounded-full">
                                                <i class="fa-solid fa-circle-check text-[10px] text-emerald-500"></i> Mutu SNI & Premium
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            @foreach($ht->specifications as $item => $val)
                                                @php
                                                    $itemLower = strtolower($item);
                                                    $iconConfig = match(true) {
                                                        str_contains($itemLower, 'ponda') || str_contains($itemLower, 'struktur') => ['fa-cubes-stacked', 'bg-amber-50 text-amber-600 border-amber-200/70'],
                                                        str_contains($itemLower, 'dinding') || str_contains($itemLower, 'bata') => ['fa-trowel-bricks', 'bg-rose-50 text-rose-600 border-rose-200/70'],
                                                        str_contains($itemLower, 'lantai') || str_contains($itemLower, 'granit') || str_contains($itemLower, 'keramik') => ['fa-table-cells', 'bg-brand-50 text-brand-600 border-brand-200/70'],
                                                        str_contains($itemLower, 'atap') || str_contains($itemLower, 'genteng') || str_contains($itemLower, 'rangka') => ['fa-house-chimney-window', 'bg-indigo-50 text-indigo-600 border-indigo-200/70'],
                                                        str_contains($itemLower, 'kusen') || str_contains($itemLower, 'pintu') || str_contains($itemLower, 'jendela') => ['fa-door-open', 'bg-emerald-50 text-emerald-600 border-emerald-200/70'],
                                                        str_contains($itemLower, 'sanit') || str_contains($itemLower, 'toilet') || str_contains($itemLower, 'kloset') || str_contains($itemLower, 'shower') => ['fa-shower', 'bg-sky-50 text-sky-600 border-sky-200/70'],
                                                        str_contains($itemLower, 'listrik') || str_contains($itemLower, 'pln') || str_contains($itemLower, 'daya') => ['fa-bolt', 'bg-yellow-50 text-yellow-600 border-yellow-200'],
                                                        str_contains($itemLower, 'air') || str_contains($itemLower, 'pdam') || str_contains($itemLower, 'sumur') => ['fa-droplet', 'bg-cyan-50 text-cyan-600 border-cyan-200/70'],
                                                        str_contains($itemLower, 'cat') || str_contains($itemLower, 'finish') => ['fa-paint-roller', 'bg-violet-50 text-violet-600 border-violet-200/70'],
                                                        str_contains($itemLower, 'plafon') => ['fa-maximize', 'bg-teal-50 text-teal-600 border-teal-200/70'],
                                                        default => ['fa-circle-check', 'bg-slate-100 text-slate-600 border-slate-200'],
                                                    };
                                                @endphp

                                                <div class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50/70 border border-slate-200/80 hover:bg-white hover:border-brand-300 hover:shadow-xs transition-all duration-200 group">
                                                    <div class="w-9 h-9 rounded-xl {{ $iconConfig[1] }} border flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform duration-200 mt-0.5">
                                                        <i class="fa-solid {{ $iconConfig[0] }} text-sm"></i>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ $item }}</span>
                                                        <p class="text-xs font-bold text-slate-800 leading-snug mt-0.5 break-words">{{ $val }}</p>
                                                    </div>
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
                <div id="siteplan" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-md">
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
                                <strong>Klik arsiran blok kavling</strong> di bawah untuk melihat foto rumah/fasad, <em>Point of View (POV)</em> lingkungan, serta denah ruangan unit tersebut!
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
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider mr-1">Pilih Blok:</span>
                        <button type="button" @click="selectedBlockFilter = 'all'"
                                aria-label="Tampilkan seluruh blok kavling"
                                :class="selectedBlockFilter === 'all' ? 'bg-brand-600 text-white font-bold shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                class="px-3 py-1 rounded-lg text-xs transition">
                            Semua Blok ({{ $project->units->count() }})
                        </button>
                        @foreach($project->units->pluck('block')->filter()->unique()->sort() as $blk)
                            <button type="button" @click="selectedBlockFilter = '{{ $blk }}'"
                                    :aria-label="'Filter blok ' + '{{ $blk }}'"
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
                                    $povPhotoUrl = $firstPov ? $firstPov->url : ($unit->images->first() ? $unit->images->first()->url : asset('images/default-pov.jpg'));

                                    $houseType = $unit->houseType;
                                    $housePhoto = $unit->facadeImages->first()?->url ?? $houseType?->images?->first()?->url ?? $project->primaryImage?->url ?? asset('images/default-project.jpg');
                                    $floorPlanPhoto = $unit->floorPlanImages->first()?->url ?? $houseType?->floor_plan_image_url ?? asset('images/default-floorplan.jpg');
                                    $slicePhoto = $unit->slice_image_url ?: asset('images/default-slice.jpg');

                                    $wPct = $unit->width_pct ?: 5.5;
                                    $hPct = $unit->height_pct ?: 6.0;
                                    $xPct = $unit->position_x - ($wPct / 2);
                                    $yPct = $unit->position_y - ($hPct / 2);

                                    $unitPriceRaw = (float) ($unit->price > 0 ? $unit->price : ($houseType ? $houseType->price : 0));

                                    $unitPayload = [
                                        'id' => $unit->id,
                                        'block' => $unit->block,
                                        'number' => $unit->number,
                                        'status' => $unit->status,
                                        'status_label' => $unit->status_label,
                                        'status_color' => $unit->status_color,
                                        'facing' => $unit->facing ?? 'Bebas Hambatan',
                                        'type_name' => $houseType ? $houseType->name : 'Standar',
                                        'price' => $unitPriceRaw,
                                        'price_formatted' => $unit->formatted_price,
                                        'land_area' => $unit->land_area ?? ($houseType ? $houseType->land_area : '-'),
                                        'building_area' => $houseType ? $houseType->building_area : '-',
                                        'bedrooms' => $houseType ? $houseType->bedrooms : '-',
                                        'bathrooms' => $houseType ? $houseType->bathrooms : '-',
                                        'house_photo' => $housePhoto,
                                        'slice_image' => $slicePhoto,
                                        'pov_photo' => $povPhotoUrl,
                                        'floor_plan' => $floorPlanPhoto,
                                        'pov_description' => $unit->pov_description ?: 'Unit strategis di kawasan cluster yang asri, nyaman, dan bebas banjir dengan sirkulasi udara optimal.',
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
                                    $uFirstPov = $u->povImages->first();
                                    $uPovPhotoUrl = $uFirstPov ? $uFirstPov->url : ($u->images->first() ? $u->images->first()->url : asset('images/default-pov.jpg'));
                                    $uHouseType = $u->houseType;
                                    $uHousePhoto = $u->facadeImages->first()?->url ?? $uHouseType?->images?->first()?->url ?? $project->primaryImage?->url ?? asset('images/default-project.jpg');
                                    $uFloorPlanPhoto = $u->floorPlanImages->first()?->url ?? $uHouseType?->floor_plan_image_url ?? asset('images/default-floorplan.jpg');
                                    $uSlicePhoto = $u->slice_image_url ?: asset('images/default-slice.jpg');
                                    $uPriceRaw = (float) ($u->price > 0 ? $u->price : ($uHouseType ? $uHouseType->price : 0));

                                    $uPayload = [
                                        'id' => $u->id,
                                        'block' => $u->block,
                                        'number' => $u->number,
                                        'status' => $u->status,
                                        'status_label' => $u->status_label,
                                        'status_color' => $u->status_color,
                                        'facing' => $u->facing ?? 'Bebas Hambatan',
                                        'type_name' => $uHouseType ? $uHouseType->name : 'Standar',
                                        'price' => $uPriceRaw,
                                        'price_formatted' => $u->formatted_price,
                                        'land_area' => $u->land_area ?? ($uHouseType ? $uHouseType->land_area : '-'),
                                        'building_area' => $uHouseType ? $uHouseType->building_area : '-',
                                        'bedrooms' => $uHouseType ? $uHouseType->bedrooms : '-',
                                        'bathrooms' => $uHouseType ? $uHouseType->bathrooms : '-',
                                        'house_photo' => $uHousePhoto,
                                        'slice_image' => $uSlicePhoto,
                                        'pov_photo' => $uPovPhotoUrl,
                                        'floor_plan' => $uFloorPlanPhoto,
                                        'pov_description' => $u->pov_description ?: 'Unit strategis di kawasan cluster yang asri, nyaman, dan bebas banjir dengan sirkulasi udara optimal.',
                                    ];
                                @endphp
                                <div @click="openPov({{ json_encode($uPayload) }})"
                                     class="p-2.5 rounded-xl border border-slate-200 hover:border-brand-500 hover:bg-brand-50/50 cursor-pointer transition text-left flex items-center justify-between">
                                    <div>
                                        <span class="text-xs font-extrabold text-slate-900 block">Kav. {{ $u->block }}-{{ $u->number }}</span>
                                        <span class="text-[10px] text-slate-500 font-medium block">{{ $u->houseType ? $u->houseType->name : '' }}</span>
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
                <div id="kpr-calculator" class="bg-gradient-to-br from-brand-50 to-nature-50 rounded-3xl p-6 sm:p-8 border border-brand-100">
                    <div class="max-w-xl mb-6">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-700">Rencana Angsuran</span>
                        <h2 class="text-xl font-bold text-slate-900 mt-0.5">Simulasi KPR {{ $project->name }}</h2>
                        <p class="text-xs text-slate-500 mt-1">Ubah harga atau persentase DP untuk melihat estimasi cicilan per bulan.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center bg-white p-6 rounded-2xl border border-brand-200/60 shadow-xs">
                        <!-- Inputs -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold uppercase text-slate-700">Plafon Harga Rumah</label>
                                <div class="text-lg font-black text-brand-600 mt-1" x-text="formatRupiah(kprPrice)"></div>
                            </div>

                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                                    <label for="detail_kpr_dp">Uang Muka (DP: <span x-text="kprDp"></span>%)</label>
                                    <span class="text-slate-900" x-text="formatRupiah(kprDpAmount)"></span>
                                </div>
                                <input type="range" id="detail_kpr_dp" aria-label="Persentase Uang Muka DP KPR" min="0" max="50" step="5" x-model.number="kprDp" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-nature-600">
                            </div>

                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                                    <label for="detail_kpr_tenor">Tenor Pinjaman</label>
                                    <span class="text-slate-900"><span x-text="kprTenor"></span> Tahun</span>
                                </div>
                                <input type="range" id="detail_kpr_tenor" aria-label="Jangka Waktu Tenor Pinjaman KPR" min="5" max="30" step="5" x-model.number="kprTenor" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-brand-600">
                            </div>

                            <div>
                                <label for="detail_bank_select" class="text-xs font-bold uppercase text-slate-700 block mb-1">Pilih Bank Rekanan</label>
                                <select id="detail_bank_select" aria-label="Pilih Bank Rekanan" @change="kprRate = parseFloat($event.target.value)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
                                    @foreach($banks ?? [] as $b)
                                        <option value="{{ $b->interest_rate }}">{{ $b->bank_name }} (Bunga {{ $b->interest_rate }}%)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Result -->
                        <div class="text-center p-6 rounded-2xl bg-brand-50/70 border border-brand-100 flex flex-col justify-center">
                            <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Estimasi Cicilan</span>
                            <div class="text-3xl font-black text-brand-700 my-2" x-text="formatRupiah(kprMonthly)">Rp 0</div>
                            <span class="text-xs text-slate-500 mb-4">per bulan selama <span x-text="kprTenor"></span> tahun</span>

                            <a :href="'https://wa.me/{{ $waClean }}?text=' + encodeURIComponent('Halo Sales {{ $siteName }}, saya ingin konsultasi KPR untuk {{ $project->name }} dengan simulasi harga ' + formatRupiah(kprPrice) + ' DP ' + kprDp + '% tenor ' + kprTenor + ' tahun.')"
                               target="_blank"
                               class="py-2.5 px-4 rounded-xl bg-nature-600 hover:bg-nature-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
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
                            <label for="booking_house_type" class="block text-xs font-bold uppercase text-slate-700 mb-1">Tipe Rumah Diminati</label>
                            <select id="booking_house_type" name="house_type_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                                <option value="">Semua Tipe / Belum Tahu</option>
                                @foreach($project->houseTypes as $ht)
                                    <option value="{{ $ht->id }}">{{ $ht->name }} - {{ $ht->formatted_price }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label for="booking_name" class="block text-xs font-bold uppercase text-slate-700 mb-1">Nama Lengkap *</label>
                            <input id="booking_name" type="text" name="name" required placeholder="Contoh: Budi Pratama" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>

                        <!-- No WhatsApp -->
                        <div>
                            <label for="booking_phone" class="block text-xs font-bold uppercase text-slate-700 mb-1">Nomor WhatsApp Aktif *</label>
                            <input id="booking_phone" type="tel" name="phone" required placeholder="Contoh: 08123456789" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>

                        <!-- Rencana Tanggal & Waktu -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="booking_preferred_date" class="block text-xs font-bold uppercase text-slate-700 mb-1">Tanggal *</label>
                                <input id="booking_preferred_date" type="date" name="preferred_date" min="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                            </div>
                            <div>
                                <label for="booking_preferred_time" class="block text-xs font-bold uppercase text-slate-700 mb-1">Waktu *</label>
                                <select id="booking_preferred_time" name="preferred_time" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-2 py-2.5 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                                    <option value="10:00 WIB (Pagi)">10:00 (Pagi)</option>
                                    <option value="13:00 WIB (Siang)">13:00 (Siang)</option>
                                    <option value="15:30 WIB (Sore)">15:30 (Sore)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label for="booking_message" class="block text-xs font-bold uppercase text-slate-700 mb-1">Catatan Tambahan</label>
                            <textarea id="booking_message" name="message" rows="2" placeholder="Contoh: Mau sekalian konsultasi berkas KPR BTN..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
                        </div>

                        <button type="submit" aria-label="Kirim formulir jadwal survei" class="w-full py-3 rounded-2xl bg-gradient-to-r from-brand-600 to-nature-600 hover:from-brand-700 hover:to-nature-700 text-white font-bold text-sm shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fa-regular fa-paper-plane"></i>
                            <span>Kirim Jadwal Survei</span>
                        </button>
                    </form>

                    <!-- Fast Contact Card -->
                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <p class="text-xs text-slate-600 text-center font-medium">Butuh respon cepat tanpa mengisi form?</p>
                        <a href="https://wa.me/{{ $waClean }}?text={{ rawurlencode('Halo Sales ' . $siteName . ', saya ingin konsultasi langsung untuk proyek ' . $project->name) }}"
                           target="_blank"
                           aria-label="Konsultasi langsung via WhatsApp"
                           class="w-full py-2.5 rounded-xl border border-nature-300 bg-nature-50 hover:bg-nature-100 text-nature-800 font-bold text-xs transition flex items-center justify-center gap-2">
                            <i class="fa-brands fa-whatsapp text-nature-600 text-lg"></i>
                            <span>Chat Langsung via WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- POPUP DETAIL UNIT & FOTO PERUMAHAN MODAL (Interactive Popup when siteplan slice or lot is clicked) -->
    <div x-show="povModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true"
         @keydown.escape.window="povModalOpen = false">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" @click="povModalOpen = false"></div>

        <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all w-full max-w-3xl border border-slate-200"
                 @click.away="povModalOpen = false">

                <template x-if="selectedUnit">
                    <div>
                        <!-- Header with Status & Close -->
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-brand-600 text-white font-black text-sm flex items-center justify-center shadow-xs">
                                    <span x-text="selectedUnit.block + '-' + selectedUnit.number"></span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-black text-slate-900 text-base sm:text-lg" x-text="'Kavling ' + selectedUnit.block + '-' + selectedUnit.number"></h3>
                                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full" :class="selectedUnit.status_color" x-text="selectedUnit.status_label"></span>
                                    </div>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                                        <span class="font-semibold text-slate-700" x-text="selectedUnit.type_name"></span> • 
                                        <span>LT: <strong x-text="selectedUnit.land_area"></strong> m²</span> • 
                                        <span>LB: <strong x-text="selectedUnit.building_area"></strong> m²</span>
                                    </p>
                                </div>
                            </div>

                            <button @click="povModalOpen = false" aria-label="Tutup detail kavling" class="w-9 h-9 rounded-full bg-white hover:bg-slate-200 text-slate-500 hover:text-slate-800 transition flex items-center justify-center shadow-xs border border-slate-200">
                                <i class="fa-solid fa-xmark text-base"></i>
                            </button>
                        </div>

                        <!-- Media Display Tabs (Foto Rumah / POV Lingkungan / Denah) -->
                        <div class="bg-slate-900 px-4 pt-3 pb-2 border-b border-slate-800">
                            <div class="flex items-center gap-2 overflow-x-auto text-xs font-bold scrollbar-none">
                                <button type="button"
                                        @click="unitModalTab = 'foto'"
                                        :class="unitModalTab === 'foto' ? 'bg-brand-600 text-white shadow-sm ring-1 ring-white/20' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'"
                                        class="px-3.5 py-1.5 rounded-xl transition flex items-center gap-2 shrink-0">
                                    <i class="fa-solid fa-house"></i>
                                    <span>Foto Rumah / Fasad</span>
                                </button>
                                <button type="button"
                                        @click="unitModalTab = 'pov'"
                                        :class="unitModalTab === 'pov' ? 'bg-brand-600 text-white shadow-sm ring-1 ring-white/20' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'"
                                        class="px-3.5 py-1.5 rounded-xl transition flex items-center gap-2 shrink-0">
                                    <i class="fa-solid fa-street-view"></i>
                                    <span>POV Lingkungan Nyata</span>
                                </button>
                                <button type="button"
                                        @click="unitModalTab = 'denah'"
                                        :class="unitModalTab === 'denah' ? 'bg-brand-600 text-white shadow-sm ring-1 ring-white/20' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'"
                                        class="px-3.5 py-1.5 rounded-xl transition flex items-center gap-2 shrink-0">
                                    <i class="fa-solid fa-compass-drafting"></i>
                                    <span>Denah Ruangan</span>
                                </button>
                            </div>
                        </div>

                        <!-- Main Media Showcase Viewer -->
                        <div class="relative h-64 sm:h-80 md:h-96 bg-slate-950 overflow-hidden flex items-center justify-center">
                            <!-- View 1: Foto Rumah / Fasad -->
                            <div x-show="unitModalTab === 'foto'" class="w-full h-full relative">
                                <img :src="selectedUnit.house_photo" :alt="'Foto Rumah Kavling ' + selectedUnit.block + '-' + selectedUnit.number" class="w-full h-full object-cover">
                                <div class="absolute bottom-3 left-3 bg-black/75 backdrop-blur-xs text-white px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-2">
                                    <i class="fa-solid fa-house text-brand-400"></i>
                                    <span>Tampak Depan (Fasad) <span x-text="selectedUnit.type_name"></span></span>
                                </div>
                            </div>

                            <!-- View 2: POV Lingkungan Nyata -->
                            <div x-show="unitModalTab === 'pov'" class="w-full h-full relative">
                                <img :src="selectedUnit.pov_photo" :alt="'POV Nyata Kavling ' + selectedUnit.block + '-' + selectedUnit.number" class="w-full h-full object-cover">
                                <div class="absolute bottom-3 left-3 bg-black/75 backdrop-blur-xs text-white px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-2">
                                    <i class="fa-solid fa-street-view text-emerald-400"></i>
                                    <span>Sudut Pandang (POV) Nyata dari Teras Kavling</span>
                                </div>
                            </div>

                            <!-- View 3: Denah Layout Ruangan -->
                            <div x-show="unitModalTab === 'denah'" class="w-full h-full relative flex items-center justify-center bg-slate-950">
                                <img :src="selectedUnit.floor_plan" :alt="'Denah Layout ' + selectedUnit.type_name" class="w-full h-full object-contain p-2">
                                <div class="absolute bottom-3 left-3 bg-black/75 backdrop-blur-xs text-white px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-2">
                                    <i class="fa-solid fa-compass-drafting text-sky-400"></i>
                                    <span>Denah Ruangan <span x-text="selectedUnit.type_name"></span></span>
                                </div>
                            </div>

                            <!-- Floating Magnify Button (Opens Lightbox) -->
                            <button type="button"
                                    @click="lightboxImg = (unitModalTab === 'foto' ? selectedUnit.house_photo : (unitModalTab === 'pov' ? selectedUnit.pov_photo : selectedUnit.floor_plan)); lightboxOpen = true"
                                    title="Perbesar Foto"
                                    class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-black/60 hover:bg-black/90 text-white flex items-center justify-center backdrop-blur-xs transition text-sm">
                                <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
                            </button>
                        </div>

                        <!-- Specifications Grid & Description -->
                        <div class="p-6 space-y-5">
                            <!-- Specifications Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                    <span class="block text-[10px] uppercase font-bold text-slate-400">Luas Bangunan</span>
                                    <span class="text-sm font-extrabold text-slate-800" x-text="selectedUnit.building_area + ' m²'"></span>
                                </div>
                                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                    <span class="block text-[10px] uppercase font-bold text-slate-400">Luas Tanah</span>
                                    <span class="text-sm font-extrabold text-slate-800" x-text="selectedUnit.land_area + ' m²'"></span>
                                </div>
                                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                    <span class="block text-[10px] uppercase font-bold text-slate-400">Kamar Tidur</span>
                                    <span class="text-sm font-extrabold text-slate-800" x-text="selectedUnit.bedrooms + ' Ruang'"></span>
                                </div>
                                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                                    <span class="block text-[10px] uppercase font-bold text-slate-400">Kamar Mandi</span>
                                    <span class="text-sm font-extrabold text-slate-800" x-text="selectedUnit.bathrooms + ' Ruang'"></span>
                                </div>
                            </div>

                            <!-- Notes / POV Description Box -->
                            <div class="p-4 rounded-2xl bg-brand-50/70 border border-brand-100 text-xs text-slate-700 leading-relaxed">
                                <div class="flex items-center gap-2 font-bold text-brand-800 mb-1">
                                    <i class="fa-solid fa-compass text-brand-600"></i>
                                    <span>Arah Hadap: <span x-text="selectedUnit.facing"></span></span>
                                </div>
                                <p x-text="selectedUnit.pov_description"></p>
                            </div>

                            <!-- Footer Price & CTAs -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2 border-t border-slate-100">
                                <div>
                                    <span class="text-[11px] text-slate-500 uppercase font-bold block">Harga Unit Kavling</span>
                                    <span class="text-2xl font-black text-nature-600" x-text="selectedUnit.price_formatted"></span>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    <button type="button"
                                            @click="calculateUnitKpr(selectedUnit.price)"
                                            class="px-4 py-2.5 rounded-xl border border-brand-200 bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold text-xs transition flex items-center gap-2">
                                        <i class="fa-solid fa-calculator"></i>
                                        <span>Simulasikan KPR</span>
                                    </button>
                                    <a :href="'https://wa.me/{{ $waClean }}?text=' + encodeURIComponent('Halo Sales {{ $siteName }}, saya ingin menanyakan Kavling ' + selectedUnit.block + '-' + selectedUnit.number + ' (Tipe ' + selectedUnit.type_name + ') seharga ' + selectedUnit.price_formatted + ' di {{ $project->name }}. Apakah unit ini masih bisa dibooking?')"
                                       target="_blank"
                                       class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-nature-600 to-emerald-600 hover:from-nature-700 hover:to-emerald-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                                        <i class="fa-brands fa-whatsapp text-base"></i>
                                        <span>Tanya Unit via WhatsApp</span>
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
        <button @click="lightboxOpen = false" aria-label="Tutup pratinjau foto perbesar" class="absolute top-6 right-6 text-white text-2xl hover:text-slate-300">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <img :src="lightboxImg" alt="Enlarged photo" class="max-w-full max-h-[90vh] rounded-2xl shadow-2xl object-contain">
    </div>

    <!-- Dedicated Interactive Floor Plan & Room Detail Slider Modal -->
    <div x-show="floorPlanModalOpen"
         x-cloak
         class="fixed inset-0 z-50 flex flex-col justify-between bg-slate-950/95 backdrop-blur-md text-white select-none transition-opacity duration-300"
         @keydown.window.escape="floorPlanModalOpen = false"
         @keydown.window.left="prevSlide()"
         @keydown.window.right="nextSlide()"
         role="dialog"
         aria-modal="true">

        <!-- Top Header Bar -->
        <div class="px-4 sm:px-6 py-3 border-b border-slate-800 bg-slate-900/80 backdrop-blur-md flex flex-wrap items-center justify-between gap-3 z-30">
            <!-- Left Info: House Type & Slide Info -->
            <div class="flex items-center gap-3 min-w-0">
                <span class="px-2.5 py-1 rounded-lg bg-nature-600 text-white font-extrabold text-[11px] uppercase tracking-wider shrink-0"
                      x-text="floorPlanSlides[currentSlideIdx]?.badge || 'Denah'"></span>
                <div class="min-w-0">
                    <h3 class="font-extrabold text-sm sm:text-base text-white truncate" x-text="floorPlanTitle + ' — ' + (floorPlanSlides[currentSlideIdx]?.title || 'Detail Ruangan')"></h3>
                    <p class="text-[11px] text-slate-300 truncate hidden sm:block" x-text="floorPlanSlides[currentSlideIdx]?.caption || floorPlanSubtitle"></p>
                </div>
            </div>

            <!-- Center/Right: Zoom Controls + Slide Counter + Close Button -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <!-- Zoom Toolbar -->
                <div class="flex items-center bg-slate-800/90 rounded-xl p-1 border border-slate-700/80 text-xs">
                    <button type="button" @click="zoomOut()" aria-label="Perkecil zoom denah" class="w-7 h-7 rounded-lg hover:bg-slate-700 flex items-center justify-center text-slate-300 hover:text-white transition" title="Zoom Out (-)">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    <span class="px-2 text-[11px] font-bold text-brand-400 min-w-[42px] text-center" x-text="Math.round(zoomLevel * 100) + '%'"></span>
                    <button type="button" @click="zoomIn()" aria-label="Perbesar zoom denah" class="w-7 h-7 rounded-lg hover:bg-slate-700 flex items-center justify-center text-slate-300 hover:text-white transition" title="Zoom In (+)">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <button type="button" @click="resetZoom()" aria-label="Reset zoom denah normal" class="ml-1 px-2 py-0.5 rounded-md hover:bg-slate-700 text-[10px] text-slate-300 hover:text-white transition" title="Reset Zoom">
                        Reset
                    </button>
                </div>

                <!-- Slide Index Indicator -->
                <span class="text-xs font-bold text-slate-300 bg-slate-800/80 border border-slate-700/80 px-3 py-1.5 rounded-xl hidden sm:inline-block">
                    <span x-text="(currentSlideIdx + 1)"></span> / <span x-text="floorPlanSlides.length"></span>
                </span>

                <!-- Close Button -->
                <button type="button" @click="floorPlanModalOpen = false" aria-label="Tutup modal denah ruangan" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-300 hover:text-white transition flex items-center justify-center" title="Tutup (Esc)">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Main Slide Viewport (With Zoom & Drag/Pan) -->
        <div class="relative flex-1 flex items-center justify-center overflow-hidden p-2 sm:p-6"
             @mousedown="startPan($event)"
             @mousemove="onPan($event)"
             @mouseup="endPan()"
             @mouseleave="endPan()"
             @wheel.prevent="$event.deltaY < 0 ? zoomIn() : zoomOut()">

            <!-- Previous Slide Button -->
            <button type="button"
                    @click.stop="prevSlide()"
                    aria-label="Slide denah sebelumnya"
                    class="absolute left-3 sm:left-6 z-20 w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-slate-900/70 hover:bg-brand-600 border border-slate-700/80 hover:border-brand-500 text-white flex items-center justify-center text-lg sm:text-xl shadow-2xl backdrop-blur-md transition-all duration-200 hover:scale-105 active:scale-95 group"
                    title="Slide Sebelumnya (Panah Kiri)">
                <i class="fa-solid fa-chevron-left group-hover:-translate-x-0.5 transition-transform"></i>
            </button>

            <!-- Image Canvas -->
            <div class="w-full h-full flex items-center justify-center transition-transform"
                 :style="'transform: scale(' + zoomLevel + ') translate(' + (panOffset.x / zoomLevel) + 'px, ' + (panOffset.y / zoomLevel) + 'px); ' + (isPanning ? 'transition: none;' : 'transition: transform 0.25s ease-out;') + ' cursor: ' + (zoomLevel > 1.0 ? (isPanning ? 'grabbing' : 'grab') : 'default');'">
                <template x-if="floorPlanSlides.length > 0 && floorPlanSlides[currentSlideIdx]">
                    <img :src="floorPlanSlides[currentSlideIdx].url"
                         :alt="floorPlanSlides[currentSlideIdx].title"
                         class="max-w-full max-h-[70vh] sm:max-h-[72vh] object-contain rounded-xl shadow-2xl pointer-events-none select-none">
                </template>
            </div>

            <!-- Next Slide Button -->
            <button type="button"
                    @click.stop="nextSlide()"
                    aria-label="Slide denah berikutnya"
                    class="absolute right-3 sm:right-6 z-20 w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-slate-900/70 hover:bg-brand-600 border border-slate-700/80 hover:border-brand-500 text-white flex items-center justify-center text-lg sm:text-xl shadow-2xl backdrop-blur-md transition-all duration-200 hover:scale-105 active:scale-95 group"
                    title="Slide Berikutnya (Panah Kanan)">
                <i class="fa-solid fa-chevron-right group-hover:translate-x-0.5 transition-transform"></i>
            </button>

            <!-- Bottom Floating Hint (if zoomed) -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 pointer-events-none bg-black/60 backdrop-blur-xs text-white/80 text-[11px] px-3 py-1 rounded-full border border-white/10 hidden sm:block">
                <template x-if="zoomLevel > 1.0">
                    <span><i class="fa-solid fa-hand text-brand-400 mr-1"></i> Klik & seret mouse untuk menggeser denah</span>
                </template>
                <template x-if="zoomLevel <= 1.0">
                    <span><i class="fa-solid fa-arrow-left mr-1"></i> Gunakan tombol panah keyboard atau geser slide <i class="fa-solid fa-arrow-right ml-1"></i></span>
                </template>
            </div>
        </div>

        <!-- Bottom Thumbnail Strip & CTA Action Bar -->
        <div class="px-4 sm:px-6 py-3 border-t border-slate-800 bg-slate-900/90 backdrop-blur-md flex flex-wrap items-center justify-between gap-4 z-30">
            <!-- Thumbnail Strip -->
            <div class="flex items-center gap-2 overflow-x-auto py-1 max-w-full sm:max-w-2xl">
                <template x-for="(slide, idx) in floorPlanSlides" :key="idx">
                    <button type="button"
                            @click="goToSlide(idx)"
                            :aria-label="'Pilih slide ' + (idx + 1) + ': ' + (slide.title || 'Denah')"
                            class="relative rounded-xl overflow-hidden shrink-0 border-2 transition-all duration-200 group w-16 h-12 sm:w-20 sm:h-14"
                            :class="currentSlideIdx === idx ? 'border-brand-500 ring-2 ring-brand-500/50 scale-105 shadow-md' : 'border-slate-700/80 opacity-60 hover:opacity-100'">
                        <img :src="slide.url" :alt="slide.title" class="w-full h-full object-cover">
                        <span class="absolute bottom-0 inset-x-0 bg-black/70 text-[9px] text-white truncate px-1 text-center font-semibold" x-text="slide.badge"></span>
                    </button>
                </template>
            </div>

            <!-- WhatsApp Consult CTA -->
            <div class="shrink-0 flex items-center gap-2">
                <a :href="'https://wa.me/{{ $waClean }}?text=' + encodeURIComponent('Halo Sales {{ $siteName }}, saya ingin konsultasi detail denah ruangan untuk ' + floorPlanTitle + ' di {{ $project->name }}.')"
                   target="_blank"
                   class="px-4 py-2 rounded-xl bg-nature-600 hover:bg-nature-700 hover:scale-105 active:scale-95 text-white font-bold text-xs shadow-md transition-all flex items-center gap-2">
                    <i class="fa-brands fa-whatsapp text-base"></i>
                    <span>Tanya Denah Tipe Ini</span>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
