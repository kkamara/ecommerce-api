<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentCardResource extends JsonResource
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
            "cardNumber" => $this->card_number,
            "cardHolderName" => $this->card_holder_name,
            "expiryDate" => $this->expiry_date,
            "cvv" => $this->cvv,
            "type" => $this->type,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
