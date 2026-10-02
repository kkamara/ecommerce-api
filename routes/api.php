<?php

use App\Http\Controllers\API\V1\DeliveryAddressController;
use App\Http\Controllers\API\V1\BillingAddressController;
use App\Http\Controllers\API\V1\PaymentCardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\HealthController;
use App\Http\Controllers\API\EmailController;

// Add third-party API routes
Route::prefix("/v1")->group(function () {
    Route::prefix("/delivery-addresses")->middleware("auth:sanctum")->group(function () {
        Route::get("/", [DeliveryAddressController::class, "index"]);
        Route::post("/", [DeliveryAddressController::class, "store"]);
        Route::get("/{deliveryAddress}", [DeliveryAddressController::class, "show"]);
        Route::patch("/{deliveryAddress}", [DeliveryAddressController::class, "update"]);
        Route::delete("/{deliveryAddress}", [DeliveryAddressController::class, "destroy"]);
    });
    Route::prefix("/billing-addresses")->middleware("auth:sanctum")->group(function () {
        Route::get("/", [BillingAddressController::class, "index"]);
        Route::post("/", [BillingAddressController::class, "store"]);
        Route::get("/{billingAddress}", [BillingAddressController::class, "show"]);
        Route::patch("/{billingAddress}", [BillingAddressController::class, "update"]);
        Route::delete("/{billingAddress}", [BillingAddressController::class, "destroy"]);
    });
    Route::prefix("/payment-cards")->middleware("auth:sanctum")->group(function () {
        Route::get("/", [PaymentCardController::class, "index"]);
        Route::post("/", [PaymentCardController::class, "store"]);
        Route::get("/{paymentCard}", [PaymentCardController::class, "show"]);
        Route::patch("/{paymentCard}", [PaymentCardController::class, "update"]);
        Route::delete("/{paymentCard}", [PaymentCardController::class, "destroy"]);
    });
    Route::prefix("/user")->group(function () {
        Route::post("/register", [UserController::class, "register"]);
        Route::post("/", [UserController::class, "login"]);
        Route::delete(
            "/",
            [UserController::class, "logout"],
        )->middleware("auth:sanctum");
        Route::get(
            "/authorise",
            [UserController::class, "authorizeUser"],
        )->middleware("auth:sanctum");
    });
});

Route::get("/health", [
    HealthController::class,
    "health",
]);
Route::get("/email", [
    EmailController::class,
    "sendEmail",
]);
