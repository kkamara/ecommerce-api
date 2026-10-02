<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\HealthController;
use App\Http\Controllers\API\EmailController;

// Add third-party API routes
Route::prefix("/v1/user")->group(function () {
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

Route::get("/health", [
    HealthController::class,
    "health",
]);
Route::get("/email", [
    EmailController::class,
    "sendEmail",
]);
