@extends('layouts.app')

@section('title', 'Tips & Berita Properti - Panduan Rumah & KPR Terlengkap')
@section('meta_description', 'Kumpulan artikel seputar tips membeli rumah pertama, syarat pengajuan KPR, panduan hukum sertifikat, dan analisis investasi properti terkini.')

@section('content')
<section class="bg-gradient-to-r from-brand-600 via-brand-500 to-nature-600 text-white py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Tips, Panduan & Edukasi Properti</h1>
        <p class="text-sm text-brand-100 mt-2 max-w-2xl leading-relaxed">
            Wawasan terpercaya untuk memudahkan langkah Anda mewujudkan hunian impian dengan aman, cerdas, dan hemat.
        </p>
    </div>
</section>

<section class="py-12 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Categories Filter -->
        <div class="flex flex-wrap items-center gap-2 mb-10">
            <a href="{{ route('blog.index') }}" class="px-4 py-2 rounded-full text-xs font-semibold transition {{ !request('category') ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                Semua Kategori
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('blog.index', ['category' => $cat]) }}" class="px-4 py-2 rounded-full text-xs font-semibold transition {{ request('category') == $cat ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($posts as $post)
                <article class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                    <div>
                        @php
                            $thumbUrl = $post->thumbnail_url;
                            if (str_contains($thumbUrl, 'images.unsplash.com')) {
                                $thumbUrl = preg_replace('/w=\d+/', 'w=500', $thumbUrl);
                                $thumbUrl = preg_replace('/q=\d+/', 'q=75', $thumbUrl);
                                if (!str_contains($thumbUrl, 'fm=webp')) {
                                    $thumbUrl .= '&fm=webp';
                                }
                            }
                        @endphp
                        <a href="{{ route('blog.show', $post->slug) }}" class="h-48 overflow-hidden bg-slate-100 block">
                            <img src="{{ $thumbUrl }}"
                                 alt="{{ $post->title }}"
                                 loading="lazy"
                                 decoding="async"
                                 width="400"
                                 height="192"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        </a>
                        <div class="p-6">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-2.5 py-1 rounded-md">
                                {{ $post->category ?? 'Tips Properti' }}
                            </span>
                            <h2 class="text-base font-bold text-slate-900 hover:text-brand-600 transition mt-3 line-clamp-2 leading-snug">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>
                            <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                                {{ strip_tags($post->content) }}
                            </p>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-0 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                        <span>{{ $post->published_at ? $post->published_at->format('d M Y') : '' }}</span>
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-brand-600 font-semibold hover:underline">
                            Baca Artikel →
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-slate-200 p-8">
                    <p class="text-sm text-slate-500">Belum ada artikel di kategori ini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $posts->links() }}
        </div>
    </div>
</section>
@endsection
