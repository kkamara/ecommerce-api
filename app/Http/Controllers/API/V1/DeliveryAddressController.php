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
            "building_name" => "nullable|string|max:255",
            "street_number" => "required|string|max:255",
            "street_name" => "required|string|max:255",
            "city" => "required|string|max:255",
            "county" => "required|string|max:255",
            "postal_code" => "required|string|max:20",
            "country" => "required|string|max:255",
            "is_default" => "nullable|boolean",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $deliveryAddress = auth()->user()
            ->deliveryAddresses()
            ->create([
                "building_name" => $request->building_name,
                "street_number" => $request->street_number,
                "street_name" => $request->street_name,
                "city" => $request->city,
                "county" => $request->county,
                "postal_code" => $request->postal_code,
                "country" => $request->country,
                "is_default" => $request->is_default ?? 0,
            ]);

        return (new DeliveryAddressResource($deliveryAddress))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(DeliveryAddress $deliveryAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeliveryAddress $deliveryAddress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeliveryAddress $deliveryAddress)
    {
        //
    }
}
