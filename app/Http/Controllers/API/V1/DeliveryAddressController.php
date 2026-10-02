<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\DeliveryAddressCollection;
use App\Http\Resources\V1\DeliveryAddressResource;
use App\Models\V1\DeliveryAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DeliveryAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deliveryAddresses = DeliveryAddress::where(
            "user_id", auth()->id()
        )->paginate(7);
        return new DeliveryAddressCollection($deliveryAddresses);
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

        $deliveryAddress = auth()->user()
            ->deliveryAddresses()
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
        
        if ($deliveryAddress->is_default) {
            auth()->user()
                ->deliveryAddresses()
                ->where('id', '!=', $deliveryAddress->id)
                ->update(['is_default' => 0]);
        }

        return (new DeliveryAddressResource($deliveryAddress))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(DeliveryAddress $deliveryAddress)
    {
        if ($deliveryAddress->user_id !== auth()->id()) {
            return response()->json([
                "error" => "Unauthorized"
            ], Response::HTTP_FORBIDDEN);
        }
        return new DeliveryAddressResource($deliveryAddress);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeliveryAddress $deliveryAddress)
    {
        if ($deliveryAddress->user_id !== auth()->id()) {
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
            $deliveryAddress->building_name = $request->buildingName;
        }
        if ($request->streetNumber) {
            $deliveryAddress->street_number = $request->streetNumber;
        }
        if ($request->streetName) {
            $deliveryAddress->street_name = $request->streetName;
        }
        if ($request->city) {
            $deliveryAddress->city = $request->city;
        }
        if ($request->county) {
            $deliveryAddress->county = $request->county;
        }
        if ($request->postalCode) {
            $deliveryAddress->postal_code = $request->postalCode;
        }
        if ($request->country) {
            $deliveryAddress->country = $request->country;
        }
        if ($request->isDefault) {
            $deliveryAddress->is_default = $request->isDefault;
        }

        $deliveryAddress->save();

        if ($deliveryAddress->is_default) {
            auth()->user()
                ->deliveryAddresses()
                ->where('id', '!=', $deliveryAddress->id)
                ->update(['is_default' => 0]);
        }

        return new DeliveryAddressResource($deliveryAddress);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeliveryAddress $deliveryAddress)
    {
        if ($deliveryAddress->user_id !== auth()->id()) {
            return response()->json([
                "error" => "Unauthorized"
            ], Response::HTTP_FORBIDDEN);
        }

        $deliveryAddress->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
