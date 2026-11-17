<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::latest()->paginate(10);

        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'content' => 'required|string',
            'thumbnail' => 'nullable|string|max:500',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $count = BlogPost::where('slug', 'like', "{$validated['slug']}%")->count();
        if ($count > 0) {
            $validated['slug'] .= '-'.($count + 1);
        }

        $validated['is_published'] = $request->has('is_published');
        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        BlogPost::create($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Artikel berhasil diterbitkan!');
    }

    public function edit(BlogPost $post)
    {
        return view('admin.blog.edit', compact('post'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'content' => 'required|string',
            'thumbnail' => 'nullable|string|max:500',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');
        if ($validated['is_published'] && ! $post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(BlogPost $post)
    {
        $post->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
