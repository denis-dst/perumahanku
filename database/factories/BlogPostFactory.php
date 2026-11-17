<?php

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(10, 999),
            'content' => fake()->paragraphs(3, true),
            'thumbnail' => null,
            'category' => fake()->randomElement(['Tips Properti', 'Info KPR', 'Desain Rumah']),
            'seo_title' => $title,
            'seo_description' => fake()->sentence(),
            'is_published' => true,
            'published_at' => now(),
        ];
    }
}
