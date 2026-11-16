<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name', 'slug', 'tagline', 'description', 'address',
        'city', 'district', 'province', 'latitude', 'longitude',
        'facilities', 'brochure_url', 'siteplan_image', 'status',
        'seo_title', 'seo_description', 'is_featured', 'sort_order',
    ];

    protected $casts = [
        'facilities' => 'array',
        'is_featured' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function houseTypes(): HasMany
    {
        return $this->hasMany(HouseType::class)->orderBy('sort_order');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('is_primary', true);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)
            ->orWhere('id', $value)
            ->firstOrFail();
    }

    public function getPriceRangeAttribute(): string
    {
        $min = $this->houseTypes()->min('price');
        $max = $this->houseTypes()->max('price');

        if (!$min && !$max) return 'Hubungi Kami';
        if ($min == $max) return 'Rp ' . number_format($min, 0, ',', '.');
        return 'Rp ' . number_format($min, 0, ',', '.') . ' - ' . number_format($max, 0, ',', '.');
    }

    public function getAvailableUnitsCountAttribute(): int
    {
        return $this->units()->where('status', 'available')->count();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCity($query, string $city)
    {
        return $query->where('city', 'LIKE', "%{$city}%");
    }
}
