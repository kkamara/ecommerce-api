<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\V1\PaymentCard;
use App\Models\V1\DeliveryAddress;
use App\Models\V1\BillingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PaymentGatewayController extends Controller
{
    /**
     * Handle the checkout process.
     */
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "amount" => "required|numeric|min:0.01",
            "paymentCardId" => "sometimes|required|integer|exists:payment_cards,id",
            "deliveryAddressId" => "sometimes|required|integer|exists:delivery_addresses,id",
            "billingAddressId" => "sometimes|required|integer|exists:billing_addresses,id",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $authUserId = $request->user()->id;

        // checkout
        if ($request->has("paymentCardId")) {
            $card = PaymentCard::find($request->input("paymentCardId"));
            if ($card->user_id !== $authUserId) {
                return response()->json([
                    "errors" => ["paymentCardId" => ["The selected payment card does not belong to the authenticated user."]]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        if ($request->has("deliveryAddressId")) {
            $deliveryAddr = DeliveryAddress::find($request->input("deliveryAddressId"));
            if ($deliveryAddr->user_id !== $authUserId) {
                return response()->json([
                    "errors" => ["deliveryAddressId" => ["The selected delivery address does not belong to the authenticated user."]]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        if ($request->has("billingAddressId")) {
            $billingAddr = BillingAddress::find($request->input("billingAddressId"));
            if ($billingAddr->user_id !== $authUserId) {
                return response()->json([
                    "errors" => ["billingAddressId" => ["The selected billing address does not belong to the authenticated user."]]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        if (!$card) {
            $defaultCard = PaymentCard::where(
                "user_id", $authUserId
            )->where("is_default", true)->first();
            if (!$defaultCard) {
                return response()->json([
                    "errors" => ["paymentCardId" => ["No default payment card found for the authenticated user."]]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        if (!$deliveryAddr) {
            $defaultDeliveryAddr = DeliveryAddress::where(
                "user_id", $authUserId
            )->where("is_default", true)->first();
            if (!$defaultDeliveryAddr) {
                return response()->json([
                    "errors" => ["deliveryAddressId" => ["No default delivery address found for the authenticated user."]]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        if (!$billingAddr) {
            $defaultBillingAddr = BillingAddress::where(
                "user_id", $authUserId
            )->where("is_default", true)->first();
            if (!$defaultBillingAddr) {
                return response()->json([
                    "errors" => ["billingAddressId" => ["No default billing address found for the authenticated user."]]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        $card = $request->input("paymentCardId") ?? $defaultCard->id;
        $deliveryAddr = $request->input("deliveryAddressId") ?? $defaultDeliveryAddr->id;
        $billingAddr = $request->input("billingAddressId") ?? $defaultBillingAddr->id;

        $request->user()->paymentGateways()->create([
            "amount" => $request->input("amount"),
            "payment_card_id" => $card,
            "delivery_address_id" => $deliveryAddr,
            "billing_address_id" => $billingAddr,
        ]);

        return response()->json([
            "message" => "Transaction completed successfully."
        ]);
    }
}
