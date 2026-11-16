@extends('layouts.admin')

@section('page_title', 'Detail Booking Survei: ' . $booking->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Detail Permintaan Survei #{{ $booking->id }}</h2>
            <p class="text-xs text-slate-500 mt-1">Dibuat pada {{ $booking->created_at->format('d M Y, H:i') }} WIB</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            ← Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <!-- Customer Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 block font-semibold uppercase text-[10px]">Nama Calon Pembeli</span>
                <span class="font-bold text-slate-900 text-sm mt-0.5 block">{{ $booking->name }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold uppercase text-[10px]">Nomor WhatsApp</span>
                <span class="font-bold text-brand-600 text-sm mt-0.5 block">{{ $booking->phone }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold uppercase text-[10px]">Email</span>
                <span class="text-slate-700 mt-0.5 block">{{ $booking->email ?? '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold uppercase text-[10px]">Perumahan & Tipe</span>
                <span class="font-bold text-slate-900 mt-0.5 block">
                    {{ $booking->project ? $booking->project->name : '-' }}
                    {{ $booking->houseType ? ' - ' . $booking->houseType->name : '' }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold uppercase text-[10px]">Rencana Tanggal</span>
                <span class="font-bold text-slate-800 mt-0.5 block">{{ $booking->preferred_date ? $booking->preferred_date->format('d F Y') : '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold uppercase text-[10px]">Waktu Kunjungan</span>
                <span class="font-bold text-slate-800 mt-0.5 block">{{ $booking->preferred_time }}</span>
            </div>
        </div>

        @if($booking->message)
            <div>
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider block mb-1">Catatan Tambahan dari Pembeli:</span>
                <p class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-700 italic">
                    "{{ $booking->message }}"
                </p>
            </div>
        @endif

        <!-- Action / Status Update Form -->
        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="pt-6 border-t border-slate-100 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Status Progres Survei *</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-semibold focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu Follow Up)</option>
                    <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed (Jadwal Terkonfirmasi dengan Sales)</option>
                    <option value="done" {{ $booking->status === 'done' ? 'selected' : '' }}>Done (Survei Selesai Dilaksanakan)</option>
                    <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Catatan Internal Marketing / Admin</label>
                <textarea name="admin_notes" rows="3" placeholder="Contoh: Sudah dihubungi, buyer minta sales pendamping Mas Rian hari Minggu jam 10..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
            </div>

            <div class="flex items-center justify-between pt-2">
                @php
                    $phoneClean = preg_replace('/[^0-9]/', '', $booking->phone);
                    if (str_starts_with($phoneClean, '0')) $phoneClean = '62' . substr($phoneClean, 1);
                    $waMsg = "Halo Bapak/Ibu {$booking->name}, kami dari Tim Marketing " . \App\Models\Setting::get('site_name') . " menindaklanjuti jadwal survei lokasi perumahan " . ($booking->project->name ?? '');
                @endphp
                <a href="https://wa.me/{{ $phoneClean }}?text={{ rawurlencode($waMsg) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-nature-500 hover:bg-nature-600 text-white font-bold text-xs shadow-md transition flex items-center gap-2">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    <span>Hubungi di WhatsApp</span>
                </a>

                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                    Simpan Perubahan Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
