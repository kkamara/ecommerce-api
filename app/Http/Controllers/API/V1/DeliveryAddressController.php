<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\DeliveryAddressCollection;
use App\Http\Resources\V1\DeliveryAddressResource;
use App\Models\V1\DeliveryAddress;
use Illuminate\Http\Request;

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
        //
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
