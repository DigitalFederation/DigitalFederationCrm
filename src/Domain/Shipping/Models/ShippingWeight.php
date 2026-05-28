<?php

namespace Domain\Shipping\Models;

use Database\Factories\ShippingWeightFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingWeight extends Model
{
    use HasFactory;

    protected $table = 'shipping_weights';

    protected $fillable = ['method_id', 'minimum_weight', 'maximum_weight', 'range'];

    protected static function newFactory(): ShippingWeightFactory
    {
        return ShippingWeightFactory::new();
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'method_id');
    }

    public function shippingPrices(): HasMany
    {
        return $this->hasMany(ShippingPrice::class, 'weight_id');
    }

    public function scopeRange($query, $range)
    {
        return $query->where('range', 'like', "%{$range}%");
    }
}
