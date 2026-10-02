<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V1\DeliveryAddress>
 */
class DeliveryAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => 1,
            "is_default" => 0,
            "building_name" => 0 === mt_rand(0, 1) ? ucwords(fake()->word()) : null,
            "street_number" => fake()->buildingNumber(),
            "street_name" => fake()->streetName(),
            "city" => fake()->city(),
            "county" => fake()->county(),
            "postal_code" => fake()->postcode(),
            "country" => fake()->country(),
        ];
    }
}
