<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BillingAddressCollection;
use App\Http\Resources\V1\BillingAddressResource;
use App\Models\V1\BillingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class BillingAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $billingAddresses = BillingAddress::where(
            "user_id", auth()->id()
        )->paginate(7);
        return new BillingAddressCollection($billingAddresses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "buildingName" => "nullable|string|max:255",
            "streetNumber" => "required|string|max:255",
            "streetName" => "required|string|max:255",
            "city" => "required|string|max:255",
            "county" => "required|string|max:255",
            "postalCode" => "required|string|max:20",
            "country" => "required|string|max:255",
            "isDefault" => "nullable|boolean",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $billingAddress = auth()->user()
            ->billingAddresses()
            ->create([
                "building_name" => $request->buildingName,
                "street_number" => $request->streetNumber,
                "street_name" => $request->streetName,
                "city" => $request->city,
                "county" => $request->county,
                "postal_code" => $request->postalCode,
                "country" => $request->country,
                "is_default" => $request->isDefault ?? 0,
            ]);
        
        if ($billingAddress->is_default) {
            auth()->user()
                ->billingAddresses()
                ->where('id', '!=', $billingAddress->id)
                ->update(['is_default' => 0]);
        }

        return (new BillingAddressResource($billingAddress))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(BillingAddress $billingAddress)
    {
        if ($billingAddress->user_id !== auth()->id()) {
            return response()->json([
                "error" => "Unauthorized"
            ], Response::HTTP_FORBIDDEN);
        }
        return new BillingAddressResource($billingAddress);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BillingAddress $billingAddress)
    {
        if ($billingAddress->user_id !== auth()->id()) {
            return response()->json([
                "error" => "Unauthorized"
            ], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            "buildingName" => "nullable|string|max:255",
            "streetNumber" => "sometimes|required|string|max:255",
            "streetName" => "sometimes|required|string|max:255",
            "city" => "sometimes|required|string|max:255",
            "county" => "sometimes|required|string|max:255",
            "postalCode" => "sometimes|required|string|max:20",
            "country" => "sometimes|required|string|max:255",
            "isDefault" => "nullable|boolean",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->buildingName) {
            $billingAddress->building_name = $request->buildingName;
        }
        if ($request->streetNumber) {
            $billingAddress->street_number = $request->streetNumber;
        }
        if ($request->streetName) {
            $billingAddress->street_name = $request->streetName;
        }
        if ($request->city) {
            $billingAddress->city = $request->city;
        }
        if ($request->county) {
            $billingAddress->county = $request->county;
        }
        if ($request->postalCode) {
            $billingAddress->postal_code = $request->postalCode;
        }
        if ($request->country) {
            $billingAddress->country = $request->country;
        }
        if ($request->isDefault) {
            $billingAddress->is_default = $request->isDefault;
        }

        $billingAddress->save();

        if ($billingAddress->is_default) {
            auth()->user()
                ->billingAddresses()
                ->where('id', '!=', $billingAddress->id)
                ->update(['is_default' => 0]);
        }

        return new BillingAddressResource($billingAddress);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BillingAddress $billingAddress)
    {
        if ($billingAddress->user_id !== auth()->id()) {
            return response()->json([
                "error" => "Unauthorized"
            ], Response::HTTP_FORBIDDEN);
        }

        $billingAddress->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
