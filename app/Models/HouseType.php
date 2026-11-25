<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class HouseType extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'slug',
        'building_area',
        'land_area',
        'bedrooms',
        'bathrooms',
        'floors',
        'price',
        'description',
        'specifications',
        'floor_plan_image',
        'is_available',
        'sort_order',
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_available' => 'boolean',
        'price' => 'decimal:0',
        'building_area' => 'integer',
        'land_area' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'floors' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('sort_order');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format($this->price, 0, ',', '.');
    }

    public function getFloorPlanImageUrlAttribute(): string
    {
        if (empty($this->floor_plan_image)) {
            return asset('images/default-floorplan.jpg');
        }

        if (str_starts_with($this->floor_plan_image, 'http://') || str_starts_with($this->floor_plan_image, 'https://')) {
            return $this->floor_plan_image;
        }

        if (str_starts_with($this->floor_plan_image, 'uploads/') || str_starts_with($this->floor_plan_image, '/uploads/')) {
            return asset(ltrim($this->floor_plan_image, '/'));
        }

        if (str_starts_with($this->floor_plan_image, 'images/') || str_starts_with($this->floor_plan_image, '/images/')) {
            return asset(ltrim($this->floor_plan_image, '/'));
        }

        return asset('storage/'.$this->floor_plan_image);
    }
}
