@extends('layouts.admin')

@section('page_title', 'Edit Perumahan: ' . $project->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Edit Proyek: {{ $project->name }}</h2>
            <p class="text-xs text-slate-500 mt-1">Perbarui data informasi perumahan.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.projects.house-types.index', $project) }}" class="text-xs font-bold text-brand-600 hover:underline">
                Kelola Tipe Rumah →
            </a>
            <a href="{{ route('admin.projects.units.index', $project) }}" class="text-xs font-bold text-nature-600 hover:underline">
                Kelola Kavling & Siteplan →
            </a>
        </div>
    </div>

    <form action="{{ route('admin.projects.update', $project) }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Nama Perumahan *</label>
                <input type="text" name="name" value="{{ old('name', $project->name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Tagline</label>
                <input type="text" name="tagline" value="{{ old('tagline', $project->tagline) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Kota *</label>
                <input type="text" name="city" value="{{ old('city', $project->city) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Kecamatan</label>
                <input type="text" name="district" value="{{ old('district', $project->district) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Provinsi</label>
                <input type="text" name="province" value="{{ old('province', $project->province) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Status Proyek *</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="active" {{ $project->status === 'active' ? 'selected' : '' }}>Active (Sedang Dipasarkan)</option>
                    <option value="coming_soon" {{ $project->status === 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                    <option value="sold_out" {{ $project->status === 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Alamat Lengkap</label>
                <textarea name="address" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('address', $project->address) }}</textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Deskripsi Lengkap</label>
                <textarea name="description" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('description', $project->description) }}</textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Fasilitas Cluster (Pisahkan baris baru)</label>
                <textarea name="facilities" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('facilities', is_array($project->facilities) ? implode("\n", $project->facilities) : '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Foto Utama URL</label>
                <input type="text" name="primary_photo_url" value="{{ old('primary_photo_url', $project->primaryImage ? $project->primaryImage->path : '') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Gambar Siteplan URL</label>
                <input type="text" name="siteplan_image" value="{{ old('siteplan_image', $project->siteplan_image) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ $project->is_featured ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-brand-600 focus:ring-0">
                <span>Tampilkan sebagai Proyek Rekomendasi di Beranda</span>
            </label>

            <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                Perbarui Perumahan
            </button>
        </div>
    </form>
</div>
@endsection
