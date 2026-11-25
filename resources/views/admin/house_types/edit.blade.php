@extends('layouts.admin')

@section('page_title', 'Edit Tipe Rumah: ' . $houseType->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Edit Tipe: {{ $houseType->name }}</h2>
            <p class="text-xs text-slate-500 mt-1">Perbarui ukuran, harga, dan spesifikasi.</p>
        </div>
        <a href="{{ route('admin.projects.house-types.index', $project) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.projects.house-types.update', [$project, $houseType]) }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Nama Tipe Rumah *</label>
                <input type="text" name="name" value="{{ old('name', $houseType->name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Harga Unit (Rupiah) *</label>
                <input type="number" name="price" value="{{ old('price', $houseType->price) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Luas Bangunan (m²)</label>
                <input type="number" name="building_area" value="{{ old('building_area', $houseType->building_area) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Luas Tanah (m²)</label>
                <input type="number" name="land_area" value="{{ old('land_area', $houseType->land_area) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Kamar Tidur</label>
                <input type="number" name="bedrooms" value="{{ old('bedrooms', $houseType->bedrooms) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Kamar Mandi</label>
                <input type="number" name="bathrooms" value="{{ old('bathrooms', $houseType->bathrooms) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Jumlah Lantai</label>
                <input type="number" name="floors" value="{{ old('floors', $houseType->floors) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            @php $currentPhoto = $houseType->images->first(); @endphp
            <!-- Foto Fasad Tipe Rumah (File Manager) -->
            <x-file-picker
                name="photo_url"
                label="Foto Rumah / Fasad Tampak Depan"
                value="{{ old('photo_url', $currentPhoto ? $currentPhoto->path : '') }}"
                folder="house_types"
                placeholder="uploads/house_types/fasad.jpg"
                help="Pilih foto fasad rumah dari public/uploads/house_types."
            />

            <!-- Floor Plan Image (File Manager) -->
            <x-file-picker
                name="floor_plan_image"
                label="Gambar Denah / Layout Ruangan"
                value="{{ old('floor_plan_image', $houseType->floor_plan_image) }}"
                folder="house_types"
                placeholder="uploads/house_types/denah.jpg"
                help="Pilih denah layout dari public/uploads/house_types."
            />

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('description', $houseType->description) }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                Perbarui Tipe Rumah
            </button>
        </div>
    </form>
</div>
@endsection
