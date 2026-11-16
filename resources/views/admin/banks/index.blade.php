@extends('layouts.admin')

@section('page_title', 'Kelola Bank Rekanan KPR')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Mitra Bank & Suku Bunga KPR</h2>
            <p class="text-xs text-slate-500 mt-1">Data bank ini digunakan dalam kalkulator simulasi KPR di seluruh halaman website.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- List Banks (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                    <tr>
                        <th class="px-6 py-4">Nama Bank</th>
                        <th class="px-6 py-4">Bunga (% p.a.)</th>
                        <th class="px-6 py-4">Maks Tenor</th>
                        <th class="px-6 py-4">Min DP</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($banks as $b)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm">
                                {{ $b->bank_name }}
                            </td>
                            <td class="px-6 py-4 font-bold text-brand-600">
                                {{ $b->interest_rate }}%
                            </td>
                            <td class="px-6 py-4">
                                {{ $b->max_tenor }} Tahun
                            </td>
                            <td class="px-6 py-4">
                                {{ $b->min_dp_percent }}%
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $b->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $b->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.banks.destroy', $b) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus bank ini?')">
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
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                Belum ada bank rekanan yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Add Bank Form (4 cols) -->
        <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-3">Tambah Bank Baru</h3>

            <form action="{{ route('admin.banks.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Nama Bank *</label>
                    <input type="text" name="bank_name" required placeholder="Contoh: Bank BNI" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Suku Bunga (% p.a.) *</label>
                    <input type="number" step="0.01" name="interest_rate" required placeholder="Contoh: 4.75" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Maksimal Tenor (Tahun) *</label>
                    <input type="number" name="max_tenor" value="25" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Minimal DP (%) *</label>
                    <input type="number" step="0.1" name="min_dp_percent" value="5" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer pt-1">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded bg-slate-100 border-slate-300 text-brand-600 focus:ring-0">
                        <span>Aktifkan di Simulasi Web</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                    + Tambah Bank Rekanan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
