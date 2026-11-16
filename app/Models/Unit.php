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
        'polygon_coords',
        'slice_image',
        'width_pct',
        'height_pct',
    ];

    protected $casts = [
        'price' => 'decimal:0',
        'land_area' => 'integer',
        'position_x' => 'float',
        'position_y' => 'float',
        'width_pct' => 'float',
        'height_pct' => 'float',
        'polygon_coords' => 'array',
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

    public function getSvgPolygonPointsAttribute(): ?string
    {
        if (empty($this->polygon_coords)) {
            // Fallback: Generate rect points from position_x, position_y, width_pct, height_pct
            $w = $this->width_pct ?: 5.0;
            $h = $this->height_pct ?: 4.0;
            $x1 = $this->position_x - ($w / 2);
            $y1 = $this->position_y - ($h / 2);
            $x2 = $x1 + $w;
            $y2 = $y1;
            $x3 = $x2;
            $y3 = $y1 + $h;
            $x4 = $x1;
            $y4 = $y3;
            return "{$x1},{$y1} {$x2},{$y2} {$x3},{$y3} {$x4},{$y4}";
        }

        if (is_array($this->polygon_coords)) {
            $points = [];
            foreach ($this->polygon_coords as $pt) {
                if (is_array($pt) && count($pt) >= 2) {
                    $points[] = "{$pt[0]},{$pt[1]}";
                }
            }
            if (!empty($points)) {
                return implode(' ', $points);
            }
        }

        return is_string($this->polygon_coords) ? $this->polygon_coords : null;
    }

    public function getSliceImageUrlAttribute(): ?string
    {
        if (!$this->slice_image) {
            return null;
        }
        if (str_starts_with($this->slice_image, 'http://') || str_starts_with($this->slice_image, 'https://')) {
            return $this->slice_image;
        }
        return asset('storage/' . $this->slice_image);
    }
}
