@extends('layouts.app')

@section('title', ($post->seo_title ?? $post->title) . ' - Tips Properti')
@section('meta_description', $post->seo_description ?? Str::limit(strip_tags($post->content), 160))
@section('meta_image', $post->thumbnail_url)

@section('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "{{ $post->title }}",
  "description": "{{ $post->seo_description ?? Str::limit(strip_tags($post->content), 160) }}",
  "image": "{{ $post->thumbnail_url }}",
  "datePublished": "{{ $post->published_at?->toIso8601String() ?? now()->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at?->toIso8601String() ?? now()->toIso8601String() }}",
  "author": {
    "@type": "Organization",
    "name": "{{ \App\Models\Setting::get('company_name', 'Alam Damai Property') }}",
    "url": "{{ url('/') }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "{{ \App\Models\Setting::get('site_name', 'Alam Damai Property') }}",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}"
    }
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ url()->current() }}"
  }
}
</script>
@endsection

@section('content')
<article class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-xs text-slate-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-brand-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('blog.index') }}" class="hover:text-brand-600">Blog</a>
            <span>/</span>
            <span class="text-slate-900 truncate">{{ $post->title }}</span>
        </div>

        <!-- Meta Header -->
        <div class="space-y-4 mb-8">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider text-brand-700 bg-brand-50 border border-brand-200">
                {{ $post->category ?? 'Edukasi Properti' }}
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                {{ $post->title }}
            </h1>
            <div class="flex items-center gap-4 text-xs text-slate-400">
                <span>Dipublikasikan: {{ $post->published_at ? $post->published_at->format('d F Y') : '' }}</span>
                <span>•</span>
                <span>Dibaca: ~4 menit</span>
            </div>
        </div>

        <!-- Feature Image -->
        <div class="rounded-3xl overflow-hidden mb-10 h-72 sm:h-96 bg-slate-100 shadow-md">
            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
        </div>

        <!-- Body Content -->
        <div class="prose prose-slate max-w-none prose-headings:font-bold prose-headings:text-slate-900 prose-p:text-slate-700 prose-p:leading-relaxed text-sm sm:text-base">
            {!! $post->content !!}
        </div>

        <!-- Share / CTA Bar -->
        <div class="mt-12 pt-8 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Tertarik mencari perumahan idaman?</h4>
                <p class="text-xs text-slate-500">Lihat katalog proyek perumahan baru kami dengan harga terbaik.</p>
            </div>
            <a href="{{ route('projects.index') }}" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition self-start sm:self-auto">
                Katalog Perumahan →
            </a>
        </div>
    </div>
</article>
@endsection
