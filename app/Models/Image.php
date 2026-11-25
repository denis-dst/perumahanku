<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'imageable_id',
        'imageable_type',
        'path',
        'caption',
        'is_primary',
        'sort_order',
        'category',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        if (empty($this->path)) {
            return asset('images/default-project.jpg');
        }

        if (str_starts_with($this->path, 'http://') || str_starts_with($this->path, 'https://')) {
            return $this->path;
        }

        if (str_starts_with($this->path, 'uploads/') || str_starts_with($this->path, '/uploads/')) {
            return asset(ltrim($this->path, '/'));
        }

        if (str_starts_with($this->path, 'images/') || str_starts_with($this->path, '/images/')) {
            return asset(ltrim($this->path, '/'));
        }

        return asset('storage/'.$this->path);
    }
}
