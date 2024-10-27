<?php

namespace Database\Factories;

use App\Models\OrderProduct;
use App\Models\{Client, Order};
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => $this->faker->randomElement(Client::all()->pluck('id')->toArray()),
            'total'     => $this->faker->numberBetween(100, 1000),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Order $order) {
            OrderProduct::factory(3)->create([
                'order_id' => $order->id,
            ]);
        });
    }


}
