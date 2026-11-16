<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::published();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $posts = $query->paginate(6)->withQueryString();
        $categories = BlogPost::where('is_published', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();
        $relatedPosts = BlogPost::published()->where('id', '!=', $post->id)->take(3)->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
