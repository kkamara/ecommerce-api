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

        $billingAddress = auth()->user()
            ->billingAddresses()
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
            "building_name" => "nullable|string|max:255",
            "street_number" => "sometimes|required|string|max:255",
            "street_name" => "sometimes|required|string|max:255",
            "city" => "sometimes|required|string|max:255",
            "county" => "sometimes|required|string|max:255",
            "postal_code" => "sometimes|required|string|max:20",
            "country" => "sometimes|required|string|max:255",
            "is_default" => "nullable|boolean",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->building_name) {
            $billingAddress->building_name = $request->building_name;
        }
        if ($request->street_number) {
            $billingAddress->street_number = $request->street_number;
        }
        if ($request->street_name) {
            $billingAddress->street_name = $request->street_name;
        }
        if ($request->city) {
            $billingAddress->city = $request->city;
        }
        if ($request->county) {
            $billingAddress->county = $request->county;
        }
        if ($request->postal_code) {
            $billingAddress->postal_code = $request->postal_code;
        }
        if ($request->country) {
            $billingAddress->country = $request->country;
        }
        if ($request->is_default) {
            $billingAddress->is_default = $request->is_default;
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
