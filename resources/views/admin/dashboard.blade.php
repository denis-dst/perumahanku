@extends('layouts.admin')

@section('page_title', 'Ringkasan Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Top Metrics Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Metric 1: Total Perumahan -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Perumahan</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $totalProjects }} Proyek</h3>
                <p class="text-[11px] text-brand-600 mt-1">Kawasan Aktif</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-city"></i>
            </div>
        </div>

        <!-- Metric 2: Unit Tersedia -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Kavling Tersedia</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $availableUnits }} Unit</h3>
                <p class="text-[11px] text-slate-500 mt-1">Dari total {{ $totalUnits }} unit</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-house-circle-check"></i>
            </div>
        </div>

        <!-- Metric 3: Unit Sold & Booked -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Terjual & Booking</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $soldUnits + $bookedUnits }} Unit</h3>
                <p class="text-[11px] text-slate-500 mt-1">{{ $soldUnits }} Terjual • {{ $bookedUnits }} Booking</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-handshake"></i>
            </div>
        </div>

        <!-- Metric 4: Total Booking Masuk -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Booking Survei</p>
                <h3 class="text-2xl font-black text-brand-600 mt-1">{{ $totalBookings }} Leads</h3>
                <p class="text-[11px] text-amber-600 font-semibold mt-1">{{ $pendingBookings }} Menunggu Follow-up</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-regular fa-calendar-check"></i>
            </div>
        </div>
    </div>

    <!-- Tables Row: Recent Bookings & Projects -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Recent Survey Bookings (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Permintaan Survei Terbaru</h3>
                    <p class="text-xs text-slate-400">Calon pembeli yang memesan jadwal survei</p>
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-brand-600 hover:underline">
                    Kelola Semua →
                </a>
            </div>

            <div class="space-y-3">
                @forelse($recentBookings as $bk)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-slate-900 truncate">{{ $bk->name }}</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $bk->status_badge }}">
                                    {{ ucfirst($bk->status) }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ $bk->project ? $bk->project->name : '-' }} {{ $bk->houseType ? '(' . $bk->houseType->name . ')' : '' }}
                            </p>
                            <p class="text-[11px] text-slate-400 mt-1 flex items-center gap-2">
                                <span><i class="fa-regular fa-calendar mr-1"></i> {{ $bk->preferred_date ? $bk->preferred_date->format('d M Y') : '-' }}</span>
                                <span>•</span>
                                <span><i class="fa-regular fa-clock mr-1"></i> {{ $bk->preferred_time }}</span>
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            @php
                                $buyerPhone = preg_replace('/[^0-9]/', '', $bk->phone);
                                if (str_starts_with($buyerPhone, '0')) $buyerPhone = '62' . substr($buyerPhone, 1);
                                $waChat = "https://wa.me/{$buyerPhone}?text=" . rawurlencode("Halo Bapak/Ibu {$bk->name}, saya dari Tim Marketing " . \App\Models\Setting::get('site_name') . " menindaklanjuti jadwal survei lokasi untuk " . ($bk->project->name ?? 'perumahan'));
                            @endphp
                            <a href="{{ $waChat }}" target="_blank" class="w-8 h-8 rounded-xl bg-nature-100 text-nature-700 hover:bg-nature-500 hover:text-white flex items-center justify-center transition" title="Chat Calon Buyer di WA">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <a href="{{ route('admin.bookings.show', $bk) }}" class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-semibold hover:border-brand-500 transition">
                                Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">Belum ada booking survei masuk.</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Projects Overview (5 cols) -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Proyek Perumahan</h3>
                        <p class="text-xs text-slate-400">Status kavling per proyek</p>
                    </div>
                    <a href="{{ route('admin.projects.create') }}" class="px-3 py-1.5 rounded-xl bg-brand-600 text-white text-xs font-semibold hover:bg-brand-700 transition">
                        + Tambah Proyek
                    </a>
                </div>

                <div class="space-y-3">
                    @foreach($recentProjects as $p)
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">{{ $p->name }}</h4>
                                <p class="text-xs text-slate-400">{{ $p->city }} • {{ $p->house_types_count }} Tipe Rumah</p>
                            </div>
                            <div class="text-right">
                                <a href="{{ route('admin.projects.units.index', $p) }}" class="text-xs font-bold text-brand-600 hover:underline block">
                                    {{ $p->units_count }} Kavling (Atur) →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Settings Callout -->
            <div class="mt-6 pt-6 border-t border-slate-100 bg-brand-50/60 p-4 rounded-2xl border border-brand-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-brand-900">Ubah Nomor WhatsApp & Info PT</p>
                        <p class="text-[11px] text-brand-700 mt-0.5">Dapat disesuaikan sewaktu-waktu di menu Pengaturan.</p>
                    </div>
                    <a href="{{ route('admin.settings.index') }}" class="px-3 py-1.5 rounded-xl bg-brand-600 text-white text-xs font-bold hover:bg-brand-700 transition">
                        Pengaturan
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
