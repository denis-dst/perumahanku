<?php

namespace Database\Factories;

use App\Models\KprBank;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KprBank>
 */
class KprBankFactory extends Factory
{
    protected $model = KprBank::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bank_name' => 'Bank '.fake()->company(),
            'logo' => null,
            'interest_rate' => 4.75,
            'max_tenor' => 25,
            'min_dp_percent' => 5.0,
            'is_active' => true,
            'sort_order' => 1,
        ];
    }
}
