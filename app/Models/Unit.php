<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unit extends Model
{
    protected $fillable = [
        'project_id',
        'house_type_id',
        'block',
        'number',
        'land_area',
        'price',
        'facing',
        'status',
        'pov_description',
        'position_x',
        'position_y',
    ];

    protected $casts = [
        'price' => 'decimal:0',
        'land_area' => 'integer',
        'position_x' => 'float',
        'position_y' => 'float',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function houseType(): BelongsTo
    {
        return $this->belongsTo(HouseType::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('sort_order');
    }

    public function povImages()
    {
        return $this->morphMany(Image::class, 'imageable')->where('category', 'pov')->orderBy('sort_order');
    }

    public function getFormattedPriceAttribute(): string
    {
        $amount = $this->price > 0 ? $this->price : ($this->houseType ? $this->houseType->price : 0);
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Tersedia',
            'booked' => 'Booking',
            'sold' => 'Terjual',
            default => 'Unknown'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'available' => 'bg-emerald-500 text-white',
            'booked' => 'bg-amber-500 text-white',
            'sold' => 'bg-rose-500 text-white',
            default => 'bg-slate-400 text-white'
        };
    }
}
