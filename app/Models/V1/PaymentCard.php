<?php

namespace App\Models\V1;

use App\Models\V1\Traits\User\TimeStampHandling;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Traits\Tappable;

class PaymentCard extends Model
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
        "is_default",
        "card_number",
        "card_holder_name",
        "expiry_date",
        "cvv",
        "type",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
