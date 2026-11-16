@extends('layouts.admin')

@section('page_title', 'Kavling & Siteplan: ' . $project->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.projects.index') }}" class="hover:underline">Perumahan</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">{{ $project->name }}</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900">Kelola Kavling & Siteplan POV</h2>
            <p class="text-xs text-slate-500 mt-1">Atur nomor kavling, status ketersediaan, posisi koordinat peta, dan foto sudut pandang (POV).</p>
        </div>
        <a href="{{ route('admin.projects.units.create', $project) }}" class="px-4 py-2.5 rounded-xl bg-nature-600 hover:bg-nature-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Kavling Baru</span>
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs text-slate-700">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                <tr>
                    <th class="px-6 py-4">Nomor Kavling</th>
                    <th class="px-6 py-4">Tipe Rumah</th>
                    <th class="px-6 py-4">Arah Hadap</th>
                    <th class="px-6 py-4">Posisi Siteplan (X, Y)</th>
                    <th class="px-6 py-4">Foto POV</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($units as $u)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-black text-slate-900 text-sm">
                            Kavling {{ $u->block ? $u->block . '-' : '' }}{{ $u->number }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700">
                            {{ $u->houseType ? $u->houseType->name : '-' }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $u->facing ?? 'Bebas' }}
                        </td>
                        <td class="px-6 py-4 font-mono text-[11px] text-brand-600">
                            X: {{ $u->position_x ?? 50 }}%, Y: {{ $u->position_y ?? 50 }}%
                        </td>
                        <td class="px-6 py-4">
                            @php $pov = $u->povImages->first(); @endphp
                            @if($pov)
                                <div class="flex items-center gap-2">
                                    <img src="{{ $pov->url }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200">
                                    <span class="text-[10px] text-emerald-600 font-bold">✓ Ada POV</span>
                                </div>
                            @else
                                <span class="text-[10px] text-slate-400">Belum Ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $u->status_color }}">
                                {{ $u->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.projects.units.edit', [$project, $u]) }}" class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-slate-100 transition" title="Edit Kavling & POV">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.projects.units.destroy', [$project, $u]) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kavling ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-slate-100 transition">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                            Belum ada kavling yang dimasukkan untuk proyek ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-slate-100">
            {{ $units->links() }}
        </div>
    </div>
</div>
@endsection
