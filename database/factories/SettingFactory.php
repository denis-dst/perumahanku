<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $key = 'setting_'.fake()->unique()->word();

        return [
            'key' => $key,
            'value' => fake()->word(),
            'group' => 'general',
            'type' => 'text',
            'label' => fake()->words(2, true),
        ];
    }
}
