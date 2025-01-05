<?php

namespace Database\Factories;

use App\Models\StoreOwner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'storeName' => fake()->company(),
            'store_owner_id' => StoreOwner::factory(),
            'location' => fake()->city()
        ];
    }
}
