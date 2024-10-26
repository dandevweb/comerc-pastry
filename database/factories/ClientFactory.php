<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'         => $this->faker->name(),
            'email'        => $this->faker->unique()->safeEmail(),
            'phone'        => $this->faker->phoneNumber(),
            'address'      => $this->faker->streetAddress(),
            'number'       => $this->faker->numberBetween(1, 100),
            'complement'   => $this->faker->secondaryAddress(),
            'neighborhood' => $this->faker->citySuffix(),
            'city'         => $this->faker->city(),
            'state'        => $this->faker->stateAbbr(),
            'birth_date'   => $this->faker->date(),
            'zip_code'     => $this->faker->postcode(),
        ];
    }
}
