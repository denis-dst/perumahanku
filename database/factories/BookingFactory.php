<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\HouseType;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

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
            'name' => fake()->name(),
            'phone' => '08'.fake()->numerify('##########'),
            'email' => fake()->safeEmail(),
            'preferred_date' => now()->addDays(2)->format('Y-m-d'),
            'preferred_time' => '10:00',
            'message' => 'Saya tertarik survei lokasi di akhir pekan.',
            'status' => 'pending',
            'admin_notes' => null,
        ];
    }
}
