@extends('layouts.admin')

@section('page_title', 'Tambah Kavling: ' . $project->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tambah Kavling Baru</h2>
            <p class="text-xs text-slate-500 mt-1">Tentukan posisi kavling di siteplan beserta foto sudut pandang (POV).</p>
        </div>
        <a href="{{ route('admin.projects.units.index', $project) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.projects.units.store', $project) }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Tipe Rumah -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Tipe Rumah *</label>
                <select name="house_type_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">Pilih Tipe Rumah</option>
                    @foreach($houseTypes as $ht)
                        <option value="{{ $ht->id }}">{{ $ht->name }} - {{ $ht->formatted_price }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Blok & Nomor -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Blok</label>
                <input type="text" name="block" placeholder="Contoh: A, B, C..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Nomor Kavling *</label>
                <input type="text" name="number" required placeholder="Contoh: 01, 02, 05 (Hook)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Status Kavling -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Status Ketersediaan *</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="available">🟢 Tersedia</option>
                    <option value="booked">🟡 Booking</option>
                    <option value="sold">🔴 Terjual</option>
                </select>
            </div>

            <!-- Arah Hadap -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Arah Hadap Rumah</label>
                <input type="text" name="facing" placeholder="Contoh: Timur (Matahari Pagi), Utara, Selatan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Luas Tanah -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Luas Tanah (m²)</label>
                <input type="number" name="land_area" placeholder="Kosongkan jika sama dengan tipe standar" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Harga Khusus -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Harga Khusus Unit (Jika ada kelebihan tanah)</label>
                <input type="number" name="price" placeholder="Kosongkan jika mengikuti harga tipe" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Posisi Koordinat Siteplan -->
            <div class="sm:col-span-2 p-4 rounded-2xl bg-brand-50/70 border border-brand-100 space-y-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-map-pin text-brand-600"></i>
                    <span class="text-xs font-bold text-brand-900 uppercase">Posisi Pin di Peta Siteplan (Persentase 0 - 100%)</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Koordinat X (Horizontal %)</label>
                        <input type="number" step="0.1" name="position_x" value="50" min="0" max="100" class="w-full bg-white border border-brand-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Koordinat Y (Vertikal %)</label>
                        <input type="number" step="0.1" name="position_y" value="50" min="0" max="100" class="w-full bg-white border border-brand-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Fitur POV (Point of View) Unit -->
            <div class="sm:col-span-2 p-4 rounded-2xl bg-nature-50/70 border border-nature-100 space-y-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-street-view text-nature-600"></i>
                    <span class="text-xs font-bold text-nature-900 uppercase">Foto & Informasi Sudut Pandang (POV) Kavling</span>
                </div>

                <x-file-picker
                    name="pov_photo_url"
                    label="Foto Pemandangan Nyata (POV)"
                    value="{{ old('pov_photo_url') }}"
                    folder="units"
                    placeholder="uploads/units/pov-kavling.jpg"
                    help="Foto asli sudut pandang menghadap luar dari kavling ini."
                />

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Deskripsi POV / Keistimewaan Sudut Pandang</label>
                    <textarea name="pov_description" rows="2" placeholder="Contoh: Menghadap taman tematik cluster & gazebo santai. Suasana sangat sejuk dan bebas polusi..." class="w-full bg-white border border-nature-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none"></textarea>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-nature-600 hover:bg-nature-700 text-white font-bold text-xs shadow-md transition">
                Simpan Kavling
            </button>
        </div>
    </form>
</div>
@endsection
