<?php

namespace App\Models\V1\Traits\User;

use Carbon\Carbon;

trait TimeStampHandling
{
    public function setCreatedAtAttribute(mixed $value): void
    {
        if ($value === null) {
            $this->attributes['created_at'] = null;

            return;
        }

        $this->attributes['created_at'] = Carbon::parse($value)
            ->toDateTimeString();
    }

    public function getCreatedAtAttribute(mixed $value): string
    {
        return Carbon::parse($value)
            ->toDateTimeString();
    }

    public function setUpdatedAtAttribute(mixed $value): void
    {
        if ($value === null) {
            $this->attributes['updated_at'] = null;

            return;
        }

        $this->attributes['updated_at'] = Carbon::parse($value)
            ->toDateTimeString();
    }

    public function getUpdatedAtAttribute(mixed $value): string
    {
        return Carbon::parse($value)
            ->toDateTimeString();
    }
}