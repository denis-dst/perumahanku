<?php

namespace Database\Factories;

use App\Models\HouseType;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<HouseType>
 */
class HouseTypeFactory extends Factory
{
    protected $model = HouseType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Tipe '.fake()->word();

        return [
            'project_id' => Project::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(10, 999),
            'building_area' => 36,
            'land_area' => 60,
            'bedrooms' => 2,
            'bathrooms' => 1,
            'floors' => 1,
            'price' => 450000000,
            'description' => fake()->sentence(),
            'specifications' => [
                'Pondasi' => 'Batu Kali & Beton Bertulang',
                'Dinding' => 'Bata Ringan / Hebel diplester aci & cat',
                'Lantai' => 'Granite Tile 60x60',
                'Atap' => 'Rangka Baja Ringan + Genteng Beton',
            ],
            'floor_plan_image' => null,
            'is_available' => true,
            'sort_order' => 1,
        ];
    }
}
