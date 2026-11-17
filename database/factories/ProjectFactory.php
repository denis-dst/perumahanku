<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company().' Residence';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(10, 999),
            'tagline' => 'Hunian Asri & Modern',
            'description' => fake()->paragraph(),
            'address' => fake()->streetAddress(),
            'city' => 'Tangerang Selatan',
            'district' => 'Serpong',
            'province' => 'Banten',
            'latitude' => -6.30000000,
            'longitude' => 106.68000000,
            'facilities' => ['One Gate System', 'CCTV 24 Jam', 'Taman Bermain Anak', 'Clubhouse'],
            'brochure_url' => null,
            'siteplan_image' => 'sample-siteplan.jpg',
            'status' => 'active',
            'seo_title' => $name.' - Perumahan Murah',
            'seo_description' => 'Katalog rumah di '.$name,
            'is_featured' => true,
            'sort_order' => 1,
        ];
    }
}
