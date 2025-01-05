<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\StoreInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'store_inventory_id' => StoreInventory::factory(),
            'total_cost' => 100.0
        ];
    }
}
