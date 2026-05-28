<?php

namespace Domain\Shipping\Models;

use Database\Factories\ShippingPriceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingPrice extends Model
{
    use HasFactory;

    protected $table = 'shipping_prices';

    protected $fillable = ['zone_id', 'weight_id', 'method_id', 'price'];

    protected static function newFactory(): ShippingPriceFactory
    {
        return ShippingPriceFactory::new();
    }

    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class, 'zone_id');
    }

    public function shippingWeight(): BelongsTo
    {
        return $this->belongsTo(ShippingWeight::class, 'weight_id');
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'method_id');
    }
}
