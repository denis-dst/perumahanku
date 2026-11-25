@extends('layouts.admin')

@section('page_title', 'Tambah Tipe Rumah: ' . $project->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tambah Tipe Rumah untuk {{ $project->name }}</h2>
            <p class="text-xs text-slate-500 mt-1">Isi ukuran luas, kamar, harga, dan spesifikasi material bangunan.</p>
        </div>
        <a href="{{ route('admin.projects.house-types.index', $project) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.projects.house-types.store', $project) }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Nama Tipe -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Nama Tipe Rumah *</label>
                <input type="text" name="name" required placeholder="Contoh: Tipe 36/60 (Asoka)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Harga -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Harga Unit (Rupiah) *</label>
                <input type="number" name="price" required placeholder="Contoh: 385000000" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- LB & LT -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Luas Bangunan (m²)</label>
                <input type="number" name="building_area" placeholder="Contoh: 36" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Luas Tanah (m²)</label>
                <input type="number" name="land_area" placeholder="Contoh: 60" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Kamar Tidur & Mandi -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Jumlah Kamar Tidur</label>
                <input type="number" name="bedrooms" value="2" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Jumlah Kamar Mandi</label>
                <input type="number" name="bathrooms" value="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Lantai -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Jumlah Lantai</label>
                <input type="number" name="floors" value="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Floor Plan Image (File Manager) -->
            <x-file-picker
                name="floor_plan_image"
                label="Gambar Denah / Layout Ruang"
                value="{{ old('floor_plan_image') }}"
                folder="house_types"
                placeholder="uploads/house_types/denah.jpg"
                help="Pilih denah layout dari public/uploads/house_types."
            />

            <!-- Deskripsi Singkat -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Deskripsi & Keunggulan Tipe</label>
                <textarea name="description" rows="3" placeholder="Pilihan ideal untuk keluarga baru dengan layout optimal..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                Simpan Tipe Rumah
            </button>
        </div>
    </form>
</div>
@endsection
