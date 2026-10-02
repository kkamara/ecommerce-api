<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            "isDefault" => $this->is_default,
            "buildingName" => $this->building_name,
            "streetNumber" => $this->street_number,
            "streetName" => $this->street_name,
            'city' => $this->city,
            'county' => $this->county,
            'postalCode' => $this->postal_code,
            'country' => $this->country,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
