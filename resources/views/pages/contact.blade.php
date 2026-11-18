@extends('layouts.app')

@section('title', 'Hubungi Kami - ' . \App\Models\Setting::get('site_name', 'Perumahanku'))

@section('content')
@php
    $siteName = \App\Models\Setting::get('site_name', 'Perumahanku');
    $companyName = \App\Models\Setting::get('company_name', 'PT Perumahanku Graha Nusantara');
    $companyPhone = \App\Models\Setting::get('company_phone', '021-88997766');
    $companyEmail = \App\Models\Setting::get('company_email', 'info@perumahanku.id');
    $companyAddress = \App\Models\Setting::get('company_address', 'Jl. Boulevard Hijau No. 128');
    $waNum = \App\Models\Setting::get('whatsapp_number', '6281234567890');
    $waClean = preg_replace('/[^0-9]/', '', $waNum);
    if (str_starts_with($waClean, '0')) $waClean = '62' . substr($waClean, 1);
@endphp

<section class="bg-gradient-to-r from-brand-600 via-brand-500 to-nature-600 text-white py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Hubungi Kami & Lokasi Kantor</h1>
        <p class="text-sm text-brand-100 mt-2 max-w-2xl leading-relaxed">
            Marketing Gallery kami buka setiap hari. Tim konsultan properti kami siap menyambut dan mendampingi kunjungan Anda.
        </p>
    </div>
</section>

<section class="py-14 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Info Cards (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                    <h2 class="text-xl font-bold text-slate-900 border-b border-slate-100 pb-4">
                        Informasi Kantor Pusat
                    </h2>

                    <div class="space-y-4 text-sm">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 block text-xs uppercase tracking-wider">Alamat Marketing Gallery</span>
                                <span class="text-xs text-slate-600 leading-relaxed block mt-0.5">{{ $companyAddress }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-nature-50 text-nature-600 flex items-center justify-center shrink-0">
                                <i class="fa-brands fa-whatsapp text-lg"></i>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 block text-xs uppercase tracking-wider">WhatsApp Hotline</span>
                                <a href="https://wa.me/{{ $waClean }}" target="_blank" class="text-xs text-brand-600 font-bold hover:underline block mt-0.5">{{ $waNum }}</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 block text-xs uppercase tracking-wider">Telepon Kantor</span>
                                <span class="text-xs text-slate-600 block mt-0.5">{{ $companyPhone }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 block text-xs uppercase tracking-wider">Email Resmi</span>
                                <span class="text-xs text-slate-600 block mt-0.5">{{ $companyEmail }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <div>
                                <span class="font-bold text-slate-800 block text-xs uppercase tracking-wider">Jam Layanan</span>
                                <span class="text-xs text-slate-600 block mt-0.5">{{ \App\Models\Setting::get('office_hours', 'Senin - Minggu: 08.30 - 17.30 WIB') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="https://wa.me/{{ $waClean }}?text={{ rawurlencode('Halo Admin ' . $siteName . ', saya ingin konsultasi langsung.') }}" target="_blank" class="w-full py-3 rounded-2xl bg-nature-600 hover:bg-nature-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                            <span>Buka Chat WhatsApp Sekarang</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Message Form (7 cols) -->
            <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Kirim Pesan / Pertanyaan</h2>
                    <p class="text-xs text-slate-500 mt-1">Kami akan membalas pesan Anda melalui WhatsApp atau Email secepat mungkin.</p>
                </div>

                <form onsubmit="event.preventDefault(); alert('Terima kasih! Pesan Anda telah kami terima, tim kami akan segera menghubungi Anda.');" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="contact_name" class="block text-xs font-bold uppercase text-slate-700 mb-1">Nama Lengkap *</label>
                            <input id="contact_name" type="text" required placeholder="Nama Anda" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div>
                            <label for="contact_phone" class="block text-xs font-bold uppercase text-slate-700 mb-1">Nomor WhatsApp *</label>
                            <input id="contact_phone" type="tel" required placeholder="Contoh: 08123456789" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                    </div>

                    <div>
                        <label for="contact_email" class="block text-xs font-bold uppercase text-slate-700 mb-1">Email</label>
                        <input id="contact_email" type="email" placeholder="email@contoh.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label for="contact_message" class="block text-xs font-bold uppercase text-slate-700 mb-1">Pesan Anda *</label>
                        <textarea id="contact_message" rows="4" required placeholder="Tuliskan pertanyaan Anda mengenai perumahan, harga, atau jadwal survei..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
                    </div>

                    <button type="submit" aria-label="Kirim Pesan Sekarang" class="px-6 py-3 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition">
                        Kirim Pesan Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
