@extends('layouts.admin')

@section('page_title', 'Edit Kavling ' . ($unit->block ? $unit->block . '-' : '') . $unit->number)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Edit Kavling {{ $unit->block ? $unit->block . '-' : '' }}{{ $unit->number }}</h2>
            <p class="text-xs text-slate-500 mt-1">Perbarui koordinat peta siteplan dan foto sudut pandang (POV).</p>
        </div>
        <a href="{{ route('admin.projects.units.index', $project) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            ← Kembali
        </a>
    </div>

    <form action="{{ route('admin.projects.units.update', [$project, $unit]) }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Tipe Rumah -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Tipe Rumah *</label>
                <select name="house_type_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    @foreach($houseTypes as $ht)
                        <option value="{{ $ht->id }}" {{ $unit->house_type_id == $ht->id ? 'selected' : '' }}>
                            {{ $ht->name }} - {{ $ht->formatted_price }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Blok & Nomor -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Blok</label>
                <input type="text" name="block" value="{{ old('block', $unit->block) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Nomor Kavling *</label>
                <input type="text" name="number" value="{{ old('number', $unit->number) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Status Kavling -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Status Ketersediaan *</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="available" {{ $unit->status === 'available' ? 'selected' : '' }}>🟢 Tersedia</option>
                    <option value="booked" {{ $unit->status === 'booked' ? 'selected' : '' }}>🟡 Booking</option>
                    <option value="sold" {{ $unit->status === 'sold' ? 'selected' : '' }}>🔴 Terjual</option>
                </select>
            </div>

            <!-- Arah Hadap -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Arah Hadap Rumah</label>
                <input type="text" name="facing" value="{{ old('facing', $unit->facing) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Luas Tanah -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Luas Tanah (m²)</label>
                <input type="number" name="land_area" value="{{ old('land_area', $unit->land_area) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Harga Khusus -->
            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Harga Khusus Unit</label>
                <input type="number" name="price" value="{{ old('price', $unit->price) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Posisi Koordinat Siteplan -->
            <div class="sm:col-span-2 p-4 rounded-2xl bg-brand-50/70 border border-brand-100 space-y-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-map-pin text-brand-600"></i>
                    <span class="text-xs font-bold text-brand-900 uppercase">Posisi Pin di Peta Siteplan (%)</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Koordinat X (Horizontal %)</label>
                        <input type="number" step="0.1" name="position_x" value="{{ old('position_x', $unit->position_x) }}" min="0" max="100" class="w-full bg-white border border-brand-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Koordinat Y (Vertikal %)</label>
                        <input type="number" step="0.1" name="position_y" value="{{ old('position_y', $unit->position_y) }}" min="0" max="100" class="w-full bg-white border border-brand-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Fitur POV (Point of View) Unit -->
            <div class="sm:col-span-2 p-4 rounded-2xl bg-nature-50/70 border border-nature-100 space-y-4">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-street-view text-nature-600"></i>
                    <span class="text-xs font-bold text-nature-900 uppercase">Foto & Informasi Sudut Pandang (POV) Kavling</span>
                </div>

                @php $currentPov = $unit->povImages->first(); @endphp
                <x-file-picker
                    name="pov_photo_url"
                    label="Foto Pemandangan Nyata (POV)"
                    value="{{ old('pov_photo_url', $currentPov ? $currentPov->path : '') }}"
                    folder="units"
                    placeholder="uploads/units/pov-kavling.jpg"
                    help="Foto asli sudut pandang menghadap luar dari kavling ini."
                />

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Deskripsi POV / Keistimewaan Sudut Pandang</label>
                    <textarea name="pov_description" rows="2" class="w-full bg-white border border-nature-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none">{{ old('pov_description', $unit->pov_description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                Perbarui Kavling
            </button>
        </div>
    </form>
</div>
@endsection
