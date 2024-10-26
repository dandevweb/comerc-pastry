<?php

namespace Database\Factories;

use App\Enums\ProductTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type'  => $this->faker->randomElement(ProductTypeEnum::cases()),
            'name'  => $this->faker->name(),
            'price' => $this->faker->numberBetween(100, 1000),
            'photo' => $this->faker->imageUrl(),
        ];
    }
}
