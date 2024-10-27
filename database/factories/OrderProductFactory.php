<?php

namespace Database\Factories;

use App\Models\{Order, Product};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->randomElement(Product::all()->pluck('id')->toArray()),
            'order_id'   => $this->faker->randomElement(Order::all()->pluck('id')->toArray()),
        ];
    }
}
