<?php

namespace Database\Factories;

use App\Models\HouseType;
use App\Models\Project;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Unit>
 */
class UnitFactory extends Factory
{
    protected $model = Unit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'house_type_id' => HouseType::factory(),
            'block' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'number' => str_pad((string) fake()->numberBetween(1, 30), 2, '0', STR_PAD_LEFT),
            'land_area' => 60,
            'price' => 450000000,
            'facing' => 'Utara',
            'status' => 'available',
            'pov_description' => 'POV pemandangan asri jalan cluster',
            'position_x' => fake()->randomFloat(2, 10, 90),
            'position_y' => fake()->randomFloat(2, 10, 90),
            'polygon_coords' => null,
            'slice_image' => null,
            'width_pct' => 5.0,
            'height_pct' => 5.0,
        ];
    }
}
