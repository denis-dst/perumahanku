@extends('layouts.admin')

@section('page_title', 'Tambah Perumahan Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Form Tambah Proyek Perumahan</h2>
            <p class="text-xs text-slate-500 mt-1">Lengkapi informasi umum proyek, alamat, dan foto siteplan.</p>
        </div>
        <a href="{{ route('admin.projects.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            ← Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.projects.store') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Nama Perumahan -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Nama Perumahan *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Grand Harmony Residence" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Tagline -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Tagline Singkat</label>
                <input type="text" name="tagline" value="{{ old('tagline') }}" placeholder="Contoh: Hunian Nyaman Ramah Lingkungan Dekat Stasiun" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Kota -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Kota / Kabupaten (Provinsi Lampung) *</label>
                <select name="city" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">-- Pilih Kabupaten / Kota di Lampung --</option>
                    @foreach(\App\Models\Project::LAMPUNG_CITIES as $c)
                        <option value="{{ $c }}" {{ old('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kecamatan -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Kecamatan</label>
                <input type="text" name="district" value="{{ old('district') }}" placeholder="Contoh: Rajabasa, Natar, Sukarame..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Provinsi -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Provinsi</label>
                <input type="text" name="province" value="{{ old('province', 'Lampung') }}" placeholder="Contoh: Lampung" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Status Proyek -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Status Pemasaran *</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="active">Active (Sedang Dipasarkan)</option>
                    <option value="coming_soon">Coming Soon (Segera Hadir)</option>
                    <option value="sold_out">Sold Out (Habis Terjual)</option>
                </select>
            </div>

            <!-- Alamat Lengkap -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Alamat Lengkap</label>
                <textarea name="address" rows="2" placeholder="Nama jalan, nomor, patokan dekat gerbang tol/stasiun..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('address') }}</textarea>
            </div>

            <!-- Deskripsi -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Deskripsi Lengkap Kawasan</label>
                <textarea name="description" rows="4" placeholder="Jelajahi keunggulan perumahan ini, konsep arsitektur, lingkungan sekitar..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('description') }}</textarea>
            </div>

            <!-- Fasilitas (Pisahkan baris baru) -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Fasilitas Cluster (Pisahkan dengan baris baru)</label>
                <textarea name="facilities" rows="3" placeholder="Security 24 Jam & One Gate System&#10;Masjid Cluster&#10;Taman Bermain Anak&#10;Clubhouse & Kolam Renang" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('facilities') }}</textarea>
            </div>

            <!-- Foto Utama URL -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">URL Foto Fasad Utama</label>
                <input type="text" name="primary_photo_url" value="{{ old('primary_photo_url') }}" placeholder="https://images.unsplash.com/..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Siteplan Image URL -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">URL Gambar Peta Siteplan</label>
                <input type="text" name="siteplan_image" value="{{ old('siteplan_image') }}" placeholder="https://images.unsplash.com/..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" class="rounded bg-slate-100 border-slate-300 text-brand-600 focus:ring-0">
                <span>Tampilkan sebagai Proyek Rekomendasi di Beranda</span>
            </label>

            <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                Simpan Perumahan
            </button>
        </div>
    </form>
</div>
@endsection
