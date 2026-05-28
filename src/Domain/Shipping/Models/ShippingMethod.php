<?php

namespace Domain\Shipping\Models;

use Database\Factories\ShippingMethodFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $table = 'shipping_methods';

    protected $fillable = ['name'];

    protected static function newFactory(): ShippingMethodFactory
    {
        return ShippingMethodFactory::new();
    }

    public function weights(): HasMany
    {
        return $this->hasMany(ShippingWeight::class, 'method_id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ShippingPrice::class, 'method_id');
    }
}
