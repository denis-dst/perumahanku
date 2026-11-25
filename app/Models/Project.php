<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Project extends Model
{
    use HasFactory;

    public const LAMPUNG_CITIES = [
        'Kota Bandar Lampung',
        'Kota Metro',
        'Kabupaten Lampung Selatan',
        'Kabupaten Lampung Tengah',
        'Kabupaten Lampung Utara',
        'Kabupaten Lampung Barat',
        'Kabupaten Lampung Timur',
        'Kabupaten Way Kanan',
        'Kabupaten Mesuji',
        'Kabupaten Tulang Bawang',
        'Kabupaten Tulang Bawang Barat',
        'Kabupaten Pesisir Barat',
        'Kabupaten Tanggamus',
        'Kabupaten Pringsewu',
        'Kabupaten Pesawaran',
    ];

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

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('sort_order');
    }

    public function primaryImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->where('is_primary', true);
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        return $this->where('slug', $value)
            ->orWhere('id', $value)
            ->firstOrFail();
    }

    public function getPriceRangeAttribute(): string
    {
        $min = $this->houseTypes()->min('price');
        $max = $this->houseTypes()->max('price');

        if (! $min && ! $max) {
            return 'Hubungi Kami';
        }

        if ($min === $max) {
            return 'Rp '.number_format($min, 0, ',', '.');
        }

        return 'Rp '.number_format($min, 0, ',', '.').' - '.number_format($max, 0, ',', '.');
    }

    public function getAvailableUnitsCountAttribute(): int
    {
        return $this->units()->where('status', 'available')->count();
    }

    public function getSiteplanImageUrlAttribute(): string
    {
        if (empty($this->siteplan_image)) {
            return asset('images/default-siteplan.jpg');
        }

        if (str_starts_with($this->siteplan_image, 'http://') || str_starts_with($this->siteplan_image, 'https://')) {
            return $this->siteplan_image;
        }

        if (str_starts_with($this->siteplan_image, 'uploads/') || str_starts_with($this->siteplan_image, '/uploads/')) {
            return asset(ltrim($this->siteplan_image, '/'));
        }

        if (str_starts_with($this->siteplan_image, 'images/') || str_starts_with($this->siteplan_image, '/images/')) {
            return asset(ltrim($this->siteplan_image, '/'));
        }

        return asset('storage/'.$this->siteplan_image);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCity(Builder $query, string $city): Builder
    {
        return $query->where('city', 'LIKE', "%{$city}%");
    }
}
