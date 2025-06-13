<?php

namespace Go2Flow\ApiPlatformConnector\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;

class ApiAuth extends Model
{
    protected $guarded = [];

    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Crypt::decryptstring($value),
            set: fn (string $value) => Crypt::encrypt($value)
        );
    }
}
