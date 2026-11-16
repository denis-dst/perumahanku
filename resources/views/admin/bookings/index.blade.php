@extends('layouts.admin')

@section('page_title', 'Kelola Booking Survei')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Leads & Janji Temu Survei Lokasi</h2>
            <p class="text-xs text-slate-500 mt-1">Daftar calon pembeli yang meminta jadwal kunjungan lokasi perumahan.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-wrap gap-4 items-center">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs w-full">
            <div>
                <select name="status" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 font-semibold focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed (Terkonfirmasi)</option>
                    <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Done (Survei Selesai)</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
                </select>
            </div>

            <div>
                <select name="project_id" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 font-semibold focus:outline-none">
                    <option value="">Semua Perumahan</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 text-white font-bold hover:bg-brand-700 transition">
                Filter Leads
            </button>

            @if(request()->hasAny(['status', 'project_id']))
                <a href="{{ route('admin.bookings.index') }}" class="text-slate-400 hover:text-slate-600 font-medium">Reset</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-6 py-4">Calon Pembeli</th>
                        <th class="px-6 py-4">Perumahan & Tipe</th>
                        <th class="px-6 py-4">Rencana Kunjungan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Dibuat Pada</th>
                        <th class="px-6 py-4 text-right">Aksi & Follow Up</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-900 text-sm block">{{ $b->name }}</span>
                                <span class="text-[11px] text-slate-500 flex items-center gap-1.5 mt-0.5">
                                    <i class="fa-solid fa-phone text-slate-400"></i> {{ $b->phone }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-800 block">{{ $b->project ? $b->project->name : '-' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $b->houseType ? $b->houseType->name : 'Semua Tipe' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800 block">{{ $b->preferred_date ? $b->preferred_date->format('d M Y') : '-' }}</span>
                                <span class="text-[11px] text-slate-500">{{ $b->preferred_time }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border {{ $b->status_badge }}">
                                    {{ $b->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400 text-[11px]">
                                {{ $b->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @php
                                    $phoneClean = preg_replace('/[^0-9]/', '', $b->phone);
                                    if (str_starts_with($phoneClean, '0')) $phoneClean = '62' . substr($phoneClean, 1);
                                    $waMsg = "Halo Bapak/Ibu {$b->name}, saya Sales " . \App\Models\Setting::get('site_name') . " menindaklanjuti rencana survei lokasi ke " . ($b->project->name ?? 'perumahan');
                                @endphp
                                <a href="https://wa.me/{{ $phoneClean }}?text={{ rawurlencode($waMsg) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-nature-100 hover:bg-nature-500 text-nature-700 hover:text-white font-bold transition inline-flex items-center gap-1.5">
                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                    <span>Chat WA</span>
                                </a>

                                <a href="{{ route('admin.bookings.show', $b) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-brand-500 hover:text-white text-slate-700 font-semibold transition inline-block">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                Belum ada data booking survei.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
