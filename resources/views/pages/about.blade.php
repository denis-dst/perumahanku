@extends('layouts.app')

@section('title', 'Tentang Kami - ' . \App\Models\Setting::get('company_name', 'PT Perumahanku Graha Nusantara'))

@section('content')
<section class="bg-gradient-to-r from-brand-600 via-brand-500 to-nature-600 text-white py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Membangun Hunian Berkualitas untuk Keluarga Indonesia</h1>
        <p class="text-sm text-brand-100 mt-2 max-w-2xl leading-relaxed">
            Mengenal lebih dekat visi, komitmen mutu, dan dedikasi kami dalam menghadirkan hunian asri dengan kepastian hukum dan nilai investasi tinggi.
        </p>
    </div>
</section>

<section class="py-14 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div class="space-y-4">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Profil Perusahaan</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                    {{ \App\Models\Setting::get('company_name', 'PT Perumahanku Graha Nusantara') }}
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Kami adalah perusahaan pengembang properti terpercaya yang berfokus pada pengembangan kawasan perumahan tapak (landed house) terpadu. Setiap proyek yang kami bangun dirancang dengan mengutamakan kenyamanan sirkulasi udara alami, penghijauan cluster, dan keamanan keluarga.
                </p>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Dengan kemitraan erat bersama bank-bank BUMN dan swasta terbesar, kami berkomitmen memberikan proses KPR yang transparan, mudah, dan terjangkau bagi para pencari rumah pertama.
                </p>
            </div>
            <div class="rounded-3xl overflow-hidden shadow-xl aspect-4/3 bg-slate-100">
                <img src="{{ asset('images/about-us.jpg') }}" alt="About Us" class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Pillars -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-slate-100">
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                <div class="w-10 h-10 rounded-xl bg-brand-500 text-white flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-base">Legalitas 100% Bersih</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Sertifikat sudah pecah per kavling (SHM/HGB Murni), IMB/PBG terbit resmi sebelum unit diserahterimakan.</p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                <div class="w-10 h-10 rounded-xl bg-nature-500 text-white flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-leaf"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-base">Konsep Ramah Lingkungan</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Plafon tinggi untuk udara sejuk tanpa boros AC, drainase tertutup anti-banjir, dan taman tematik asri.</p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                <div class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center font-bold text-lg mb-3">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-base">Pendampingan KPR Tuntas</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Tim sales kami mendampingi pengumpulan berkas dan konsultasi BI checking sampai persetujuan bank selesai.</p>
            </div>
        </div>
    </div>
</section>
@endsection
