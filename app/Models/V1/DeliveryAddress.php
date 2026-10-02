<?php

namespace App\Models\V1;

use App\Models\V1\Traits\User\TimeStampHandling;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Traits\Tappable;

class DeliveryAddress extends Model
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
        "building_name",
        "street_number",
        "street_name",
        "city",
        "county",
        "postal_code",
        "country",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
