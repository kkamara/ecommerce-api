<?php

namespace Database\Seeders\V1;

use App\Models\V1\DeliveryAddress;
use App\Models\V1\BillingAddress;
use App\Models\V1\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder as IlluminateSeeder;

class Seeder extends IlluminateSeeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            "first_name" => "Jane",
            "last_name" => "Doe",
            "email" => "jane@example.com",
        ]);

        $userId = $user->id;

        DeliveryAddress::factory()->create([
            "user_id" => $userId,
        ]);
        DeliveryAddress::factory()->create([
            "user_id" => $userId,
            "is_default" => 1,
        ]);

        BillingAddress::factory()->create([
            "user_id" => $userId,
        ]);
        BillingAddress::factory()->create([
            "user_id" => $userId,
            "is_default" => 1,
        ]);
    }
}
