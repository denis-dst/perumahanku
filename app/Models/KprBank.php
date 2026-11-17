<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KprBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'logo',
        'interest_rate',
        'max_tenor',
        'min_dp_percent',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'interest_rate' => 'float',
        'max_tenor' => 'integer',
        'min_dp_percent' => 'float',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
