@extends('layouts.app')

@section('title', 'Kalkulator Simulasi KPR - Hitung Cicilan Rumah Terkini')
@section('meta_description', 'Kalkulator simulasi KPR interaktif untuk membandingkan angsuran bulanan antar bank rekanan seperti Bank BTN, Mandiri, BCA, BSI, dan BRI.')

@section('content')
@php
    $siteName = \App\Models\Setting::get('site_name', 'Perumahanku');
    $waNum = \App\Models\Setting::get('whatsapp_number', '6281234567890');
    $waClean = preg_replace('/[^0-9]/', '', $waNum);
    if (str_starts_with($waClean, '0')) $waClean = '62' . substr($waClean, 1);
@endphp

<!-- Header Banner -->
<section class="bg-gradient-to-r from-brand-600 via-brand-500 to-nature-600 text-white py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Kalkulator Simulasi KPR Interaktif</h1>
        <p class="text-sm text-brand-100 mt-2 max-w-2xl leading-relaxed">
            Hitung perkiraan angsuran bulanan rumah idaman Anda dan bandingkan suku bunga dari berbagai bank rekanan terpercaya di Indonesia.
        </p>
    </div>
</section>

<!-- Interactive Calculator Section -->
<section class="py-14 bg-slate-50" x-data="{
    price: {{ $initialPrice }},
    dpPercent: {{ $initialDpPercent }},
    tenor: {{ $initialTenor }},
    get dpAmount() {
        return (this.price * this.dpPercent) / 100;
    },
    get loanAmount() {
        return this.price - this.dpAmount;
    },
    calculateInstallment(rate) {
        let p = this.loanAmount;
        let r = (rate / 100) / 12;
        let n = this.tenor * 12;
        if (p <= 0 || n <= 0) return 0;
        let c = (p * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
        return Math.round(c);
    },
    formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Controls (5 cols) -->
            <div class="lg:col-span-5 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-4">
                    <i class="fa-solid fa-calculator text-brand-600"></i>
                    <span>Parameter Pinjaman</span>
                </h2>

                <!-- Harga Rumah -->
                <div>
                    <div class="flex justify-between items-baseline mb-2">
                        <label class="text-xs font-bold uppercase text-slate-600">Harga Properti</label>
                        <span class="text-base font-extrabold text-brand-600" x-text="formatRupiah(price)"></span>
                    </div>
                    <input type="range" min="100000000" max="2500000000" step="25000000" x-model.number="price" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-brand-600">
                    <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                        <span>100 Jt</span>
                        <span>1 M</span>
                        <span>2.5 M</span>
                    </div>
                </div>

                <!-- Uang Muka (DP) -->
                <div>
                    <div class="flex justify-between items-baseline mb-2">
                        <label class="text-xs font-bold uppercase text-slate-600">Uang Muka (DP)</label>
                        <span class="text-sm font-bold text-slate-800"><span x-text="dpPercent"></span>% (<span x-text="formatRupiah(dpAmount)"></span>)</span>
                    </div>
                    <input type="range" min="0" max="50" step="5" x-model.number="dpPercent" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-nature-500">
                    <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                        <span>0% (Promo)</span>
                        <span>10%</span>
                        <span>50%</span>
                    </div>
                </div>

                <!-- Jangka Waktu (Tenor) -->
                <div>
                    <div class="flex justify-between items-baseline mb-2">
                        <label class="text-xs font-bold uppercase text-slate-600">Jangka Waktu (Tenor)</label>
                        <span class="text-sm font-bold text-slate-800"><span x-text="tenor"></span> Tahun (<span x-text="tenor * 12"></span> Bulan)</span>
                    </div>
                    <input type="range" min="5" max="30" step="5" x-model.number="tenor" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-brand-600">
                    <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                        <span>5 Th</span>
                        <span>15 Th</span>
                        <span>30 Th</span>
                    </div>
                </div>

                <!-- Ringkasan Pokok Pinjaman -->
                <div class="p-4 rounded-2xl bg-brand-50 border border-brand-100 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Harga Rumah:</span>
                        <span class="font-bold text-slate-900" x-text="formatRupiah(price)"></span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Total Uang Muka (DP):</span>
                        <span class="font-bold text-slate-900" x-text="formatRupiah(dpAmount)"></span>
                    </div>
                    <div class="flex justify-between text-brand-700 font-extrabold pt-2 border-t border-brand-200">
                        <span>Plafon Pinjaman KPR:</span>
                        <span x-text="formatRupiah(loanAmount)"></span>
                    </div>
                </div>

                <!-- Fast WhatsApp Consultation -->
                <div class="pt-2">
                    <a :href="'https://wa.me/{{ $waClean }}?text=' + encodeURIComponent('Halo Sales {{ $siteName }}, saya ingin konsultasi pengajuan KPR untuk rumah harga ' + formatRupiah(price) + ' dengan DP ' + dpPercent + '% tenor ' + tenor + ' tahun.')"
                       target="_blank"
                       class="w-full py-3 rounded-2xl bg-nature-500 hover:bg-nature-600 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        <span>Konsultasi Syarat KPR via WA</span>
                    </a>
                </div>
            </div>

            <!-- Right: Bank Comparison Grid (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Perbandingan Simulasi per Bank Rekanan</h2>
                    <p class="text-xs text-slate-500 mt-1">Estimasi angsuran dihitung menggunakan suku bunga promo efektif tahun pertama.</p>
                </div>

                <div class="space-y-4">
                    @foreach($banks as $bank)
                        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs hover:border-brand-400 hover:shadow-md transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-xs">
                                        <i class="fa-solid fa-building-columns"></i>
                                    </span>
                                    <h3 class="font-bold text-slate-900 text-base">{{ $bank->bank_name }}</h3>
                                </div>
                                <div class="text-xs text-slate-500 flex flex-wrap gap-3 pt-1">
                                    <span>Suku Bunga: <strong class="text-brand-600">{{ $bank->interest_rate }}% eff. p.a.</strong></span>
                                    <span>•</span>
                                    <span>Maks Tenor: <strong>{{ $bank->max_tenor }} Tahun</strong></span>
                                    <span>•</span>
                                    <span>Min DP: <strong>{{ $bank->min_dp_percent }}%</strong></span>
                                </div>
                            </div>

                            <div class="text-left sm:text-right pt-2 sm:pt-0 border-t sm:border-0 border-slate-100">
                                <span class="text-[10px] text-slate-400 uppercase font-semibold block">Estimasi Angsuran</span>
                                <span class="text-xl font-extrabold text-nature-600 block"
                                      x-text="formatRupiah(calculateInstallment({{ $bank->interest_rate }}))">
                                </span>
                                <span class="text-[10px] text-slate-400">/ bulan</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Info Box Requirements -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200 text-xs text-slate-600 space-y-3">
                    <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-brand-500"></i>
                        <span>Syarat Umum Berkas Pengajuan KPR</span>
                    </h4>
                    <ul class="list-disc pl-5 space-y-1.5 leading-relaxed text-slate-500">
                        <li>KTP Pemohon & Pasangan (bagi yang sudah menikah)</li>
                        <li>Kartu Keluarga (KK) & Surat Nikah / Akta Cerai</li>
                        <li>NPWP Pribadi & SPT Tahunan PPh 21</li>
                        <li>Slip Gaji 3 bulan terakhir & Surat Keterangan Kerja Asli (Karyawan)</li>
                        <li>Rekening koran 3-6 bulan terakhir</li>
                        <li>Bebas catatan macet di SLIK OJK (BI Checking)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
