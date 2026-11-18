{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Halaman Utama -->
    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Katalog Perumahan -->
    <url>
        <loc>{{ route('projects.index') }}</loc>
        <lastmod>{{ $projects->first()?->updated_at?->toDateString() ?? now()->toDateString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- Simulasi KPR -->
    <url>
        <loc>{{ route('kpr.index') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Blog & Artikel Tips Properti -->
    <url>
        <loc>{{ route('blog.index') }}</loc>
        <lastmod>{{ $posts->first()?->updated_at?->toDateString() ?? now()->toDateString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Tentang Kami -->
    <url>
        <loc>{{ route('pages.about') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    <!-- Hubungi Kami -->
    <url>
        <loc>{{ route('pages.contact') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>

    <!-- 15 Wilayah Kabupaten & Kota se-Provinsi Lampung -->
    @foreach($cities as $city)
    <url>
        <loc>{{ route('projects.city', \Illuminate\Support\Str::slug($city)) }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    <!-- Detail Proyek Perumahan Aktif -->
    @foreach($projects as $p)
    <url>
        <loc>{{ route('projects.show', $p->slug) }}</loc>
        <lastmod>{{ $p->updated_at?->toDateString() ?? now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

    <!-- Artikel Blog Published -->
    @foreach($posts as $post)
    <url>
        <loc>{{ route('blog.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at?->toDateString() ?? now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
</urlset>
