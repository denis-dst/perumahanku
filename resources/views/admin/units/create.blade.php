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

            <!-- Fitur Media & Foto Kavling (3 Pilihan Slot Foto) -->
            <div class="sm:col-span-2 p-5 sm:p-6 rounded-3xl bg-slate-50 border border-slate-200/80 space-y-6">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-100 text-brand-700 text-xs font-bold mb-1.5">
                        <i class="fa-solid fa-images"></i>
                        <span>Media & Foto Kavling</span>
                    </div>
                    <h3 class="text-sm font-extrabold text-slate-900">Pilihan Penempatan Foto Kavling</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Klik langsung kotak gambar di bawah untuk membuka File Manager dan mengambil atau mengunggah foto.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- 1. Foto Rumah / Fasad -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 uppercase tracking-wide flex items-center gap-1.5">
                                <i class="fa-solid fa-house text-brand-600"></i> Foto Rumah / Fasad
                            </span>
                            <span class="text-[10px] text-slate-400 font-semibold">(Opsional)</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-snug">
                            Tampak depan rumah unit ini. Jika kosong, otomatis memakai foto tipe rumah.
                        </p>
                        <x-file-picker
                            name="facade_photo_url"
                            value="{{ old('facade_photo_url') }}"
                            folder="house_types"
                            placeholder="uploads/house_types/fasad.jpg"
                        />
                    </div>

                    <!-- 2. Foto POV Lingkungan Nyata -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 uppercase tracking-wide flex items-center gap-1.5">
                                <i class="fa-solid fa-street-view text-emerald-600"></i> POV Lingkungan Nyata
                            </span>
                            <span class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-full">Kavling</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-snug">
                            Pemandangan nyata dari depan kavling menghadap luar cluster/taman.
                        </p>
                        <x-file-picker
                            name="pov_photo_url"
                            value="{{ old('pov_photo_url') }}"
                            folder="units"
                            placeholder="uploads/units/pov-kavling.jpg"
                        />
                    </div>

                    <!-- 3. Denah Ruangan -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 uppercase tracking-wide flex items-center gap-1.5">
                                <i class="fa-solid fa-compass-drafting text-sky-600"></i> Denah Ruangan
                            </span>
                            <span class="text-[10px] text-slate-400 font-semibold">(Opsional)</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-snug">
                            Denah ruangan unit ini. Jika kosong, otomatis memakai denah tipe rumah.
                        </p>
                        <x-file-picker
                            name="floor_plan_photo_url"
                            value="{{ old('floor_plan_photo_url') }}"
                            folder="house_types"
                            placeholder="uploads/house_types/denah.jpg"
                        />
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200/80">
                    <label class="block text-[11px] font-bold uppercase text-slate-700 mb-1">
                        <i class="fa-solid fa-pen-to-square text-slate-400 mr-1"></i> Catatan Deskripsi POV / Keistimewaan Lingkungan
                    </label>
                    <textarea name="pov_description" rows="2" placeholder="Contoh: Menghadap taman tematik cluster, suasana asri dan bebas polusi..." class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('pov_description') }}</textarea>
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
