<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V1\PaymentCard>
 */
class PaymentCardFactory extends Factory
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
            "card_number" => fake()->creditCardNumber(),
            "card_holder_name" => fake()->name(),
            "expiry_date" => fake()->creditCardExpirationDateString(false),
            "cvv" => fake()->numberBetween(100, 999),
            "type" => strtolower(fake()->creditCardType()),
        ];
    }
}
