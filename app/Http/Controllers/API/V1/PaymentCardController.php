<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\V1\PaymentCard;
use App\Http\Resources\V1\PaymentCardResource;
use App\Http\Resources\V1\PaymentCardCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PaymentCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentCards = PaymentCard::where(
            "user_id", auth()->id()
        )->paginate(7);
        return new PaymentCardCollection($paymentCards);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "cardNumber" => "required|string|max:16",
            "cardHolderName" => "required|string|max:50",
            "expiryDate" => "required|string|max:5",
            "cvv" => "required|integer|digits:3",
            "type" => "required|string|max:20",
            "isDefault" => "nullable|boolean",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $paymentCard = auth()->user()
            ->paymentCards()
            ->create([
                "card_number" => $request->cardNumber,
                "card_holder_name" => $request->cardHolderName,
                "expiry_date" => $request->expiryDate,
                "cvv" => $request->cvv,
                "type" => $request->type,
                "is_default" => $request->isDefault ?? 0,
            ]);
        
        if ($paymentCard->is_default) {
            auth()->user()
                ->paymentCards()
                ->where('id', '!=', $paymentCard->id)
                ->update(['is_default' => 0]);
        }

        return (new PaymentCardResource($paymentCard))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentCard $paymentCard)
    {
        if ($paymentCard->user_id !== auth()->id()) {
            return response()->json([
                "error" => "Unauthorized"
            ], Response::HTTP_FORBIDDEN);
        }
        return new PaymentCardResource($paymentCard);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentCard $paymentCard)
    {
        if ($paymentCard->user_id !== auth()->id()) {
            return response()->json([
                "error" => "Unauthorized"
            ], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            "cardNumber" => "sometimes|required|string|max:16",
            "cardHolderName" => "sometimes|required|string|max:50",
            "expiryDate" => "sometimes|required|string|max:5",
            "cvv" => "sometimes|required|integer|digits:3",
            "type" => "sometimes|required|string|max:20",
            "isDefault" => "nullable|boolean",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->cardNumber) {
            $paymentCard->card_number = $request->cardNumber;
        }
        if ($request->cardHolderName) {
            $paymentCard->card_holder_name = $request->cardHolderName;
        }
        if ($request->expiryDate) {
            $paymentCard->expiry_date = $request->expiryDate;
        }
        if ($request->cvv) {
            $paymentCard->cvv = $request->cvv;
        }
        if ($request->type) {
            $paymentCard->type = $request->type;
        }
        if ($request->isDefault) {
            $paymentCard->is_default = $request->isDefault;
        }

        $paymentCard->save();

        if ($paymentCard->is_default) {
            auth()->user()
                ->paymentCards()
                ->where('id', '!=', $paymentCard->id)
                ->update(['is_default' => 0]);
        }

        return new PaymentCardResource($paymentCard);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentCard $paymentCard)
    {
        if ($paymentCard->user_id !== auth()->id()) {
            return response()->json([
                "error" => "Unauthorized"
            ], Response::HTTP_FORBIDDEN);
        }

        $paymentCard->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
