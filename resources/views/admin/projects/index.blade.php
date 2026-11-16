@extends('layouts.admin')

@section('page_title', 'Kelola Proyek Perumahan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Daftar Perumahan</h2>
            <p class="text-xs text-slate-500 mt-1">Kelola data perumahan, denah tipe rumah, dan pemetaan kavling siteplan.</p>
        </div>
        <a href="{{ route('admin.projects.create') }}" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Perumahan Baru</span>
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-6 py-4">Perumahan</th>
                        <th class="px-6 py-4">Lokasi</th>
                        <th class="px-6 py-4">Tipe Rumah</th>
                        <th class="px-6 py-4">Kavling & Siteplan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projects as $p)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $p->primaryImage ? $p->primaryImage->url : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=150&q=80' }}" class="w-12 h-12 rounded-xl object-cover shrink-0 border border-slate-200">
                                    <div>
                                        <span class="font-bold text-slate-900 text-sm block">{{ $p->name }}</span>
                                        <span class="text-[11px] text-slate-400 block">{{ $p->slug }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-800">{{ $p->city }}</span>
                                <span class="text-slate-400 block text-[11px]">{{ $p->district }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.projects.house-types.index', $p) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-brand-50 text-brand-700 font-bold hover:bg-brand-100 transition">
                                    <i class="fa-solid fa-layer-group"></i>
                                    <span>{{ $p->house_types_count }} Tipe Rumah</span>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1 items-start">
                                    <a href="{{ route('admin.projects.siteplan.index', $p) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-nature-600 text-white font-bold hover:bg-nature-700 shadow-xs transition">
                                        <i class="fa-solid fa-map-location-dot"></i>
                                        <span>Peta & Slicer Siteplan</span>
                                    </a>
                                    <a href="{{ route('admin.projects.units.index', $p) }}" class="text-[11px] text-slate-500 hover:text-brand-600 font-medium ml-1">
                                        {{ $p->units_count }} Kavling terdaftar →
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $p->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('projects.show', $p->slug) }}" target="_blank" class="p-2 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-slate-100 transition" title="Lihat di Web">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                                <a href="{{ route('admin.projects.edit', $p) }}" class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-slate-100 transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.projects.destroy', $p) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus proyek ini beserta seluruh tipe rumah dan unitnya?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-slate-100 transition" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                Belum ada proyek perumahan. Klik tombol "Tambah Perumahan Baru" untuk memulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $projects->links() }}
        </div>
    </div>
</div>
@endsection
