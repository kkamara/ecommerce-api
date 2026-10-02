<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->decimal('amount', 10, 2);
            $table->unsignedBigInteger("payment_card_id")->nullable();
            $table->unsignedBigInteger("delivery_address_id")->nullable();
            $table->unsignedBigInteger("billing_address_id")->nullable();
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("payment_card_id")->references("id")->on("payment_cards")->onDelete("set null");
            $table->foreign("delivery_address_id")->references("id")->on("delivery_addresses")->onDelete("set null");
            $table->foreign("billing_address_id")->references("id")->on("billing_addresses")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
