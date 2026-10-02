<?php

namespace App\Models\V1;

use App\Models\V1\Traits\User\TimeStampHandling;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Traits\Tappable;

class PaymentGateway extends Model
{
    use Tappable;
    use TimeStampHandling;
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "user_id",
        "amount",
        "payment_card_id",
        "delivery_address_id",
        "billing_address_id",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentCard()
    {
        return $this->belongsTo(PaymentCard::class);
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(DeliveryAddress::class);
    }

    public function billingAddress()
    {
        return $this->belongsTo(BillingAddress::class);
    }
}
