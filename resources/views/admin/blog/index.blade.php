@extends('layouts.admin')

@section('page_title', 'Kelola Artikel & Tips SEO')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Daftar Artikel & Tips Edukasi</h2>
            <p class="text-xs text-slate-500 mt-1">Artikel blog membantu perumahan Anda cepat muncul di pencarian Google (SEO Organic).</p>
        </div>
        <a href="{{ route('admin.blog.create') }}" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md transition flex items-center gap-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i>
            <span>Tulis Artikel Baru</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs text-slate-700">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-bold">
                <tr>
                    <th class="px-6 py-4">Judul Artikel</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Tanggal Publikasi</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($posts as $p)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-900 text-sm block line-clamp-1">{{ $p->title }}</span>
                            <span class="text-[11px] text-slate-400 block">{{ $p->slug }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-semibold text-[11px]">
                                {{ $p->category ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $p->is_published ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                {{ $p->is_published ? 'Terbit' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400">
                            {{ $p->published_at ? $p->published_at->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('blog.show', $p->slug) }}" target="_blank" class="p-2 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-slate-100 transition" title="Lihat di Web">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                            <a href="{{ route('admin.blog.edit', $p) }}" class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-slate-100 transition" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.blog.destroy', $p) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus artikel ini?')">
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
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                            Belum ada artikel. Klik tombol di atas untuk menulis artikel baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-slate-100">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
