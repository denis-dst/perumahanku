<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'category',
        'seo_title',
        'seo_description',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->whereNotNull('published_at')->orderByDesc('published_at');
    }

    public function getThumbnailUrlAttribute(): string
    {
        if (! $this->thumbnail) {
            return asset('images/default-blog.jpg');
        }
        if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
            return $this->thumbnail;
        }

        if (str_starts_with($this->thumbnail, 'uploads/') || str_starts_with($this->thumbnail, '/uploads/')) {
            return asset(ltrim($this->thumbnail, '/'));
        }

        if (str_starts_with($this->thumbnail, 'images/') || str_starts_with($this->thumbnail, '/images/')) {
            return asset(ltrim($this->thumbnail, '/'));
        }

        return asset('storage/'.$this->thumbnail);
    }
}
