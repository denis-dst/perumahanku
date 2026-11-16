@extends('layouts.admin')

@section('page_title', 'Tipe Rumah: ' . $project->name)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.projects.index') }}" class="hover:underline">Perumahan</a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">{{ $project->name }}</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900">Kelola Tipe Rumah</h2>
        </div>
        <a href="{{ route('admin.projects.house-types.create', $project) }}" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Tipe Rumah</span>
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs text-slate-700">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                <tr>
                    <th class="px-6 py-4">Nama Tipe</th>
                    <th class="px-6 py-4">LB / LT</th>
                    <th class="px-6 py-4">Kamar Tidur / Mandi</th>
                    <th class="px-6 py-4">Lantai</th>
                    <th class="px-6 py-4">Harga Unit</th>
                    <th class="px-6 py-4">Kavling Terhubung</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($houseTypes as $ht)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $ht->name }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-700">{{ $ht->building_area }} m² / {{ $ht->land_area }} m²</td>
                        <td class="px-6 py-4">{{ $ht->bedrooms }} KT / {{ $ht->bathrooms }} KM</td>
                        <td class="px-6 py-4">{{ $ht->floors }} Lantai</td>
                        <td class="px-6 py-4 font-bold text-nature-600">{{ $ht->formatted_price }}</td>
                        <td class="px-6 py-4 font-semibold text-brand-600">{{ $ht->units_count }} Kavling</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.projects.house-types.edit', [$project, $ht]) }}" class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-slate-100 transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.projects.house-types.destroy', [$project, $ht]) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus tipe rumah ini?')">
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
                            Belum ada tipe rumah untuk proyek ini. Klik tombol tambah di atas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
