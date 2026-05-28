<?php

namespace Domain\Shipping\Models;

use App\Models\Country;
use Database\Factories\ShippingZoneFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ShippingZone extends Model
{
    use HasFactory;

    protected $table = 'shipping_zones';

    protected $fillable = ['name'];

    protected static function newFactory(): ShippingZoneFactory
    {
        return ShippingZoneFactory::new();
    }

    // Define the new relationship with ShippingSubZone
    public function subZones(): BelongsToMany
    {
        return $this->belongsToMany(ShippingSubZone::class, 'shipping_zone_sub_zone', 'zone_id', 'sub_zone_id');
    }

    public function shippingPrices(): HasMany
    {
        return $this->hasMany(ShippingPrice::class, 'zone_id');
    }

    // Define a relationship with countries through sub-zones
    public function countries(): HasManyThrough
    {
        return $this->hasManyThrough(
            Country::class,
            ShippingSubZone::class,
            'zone_id',      // Foreign key on the ShippingSubZone table
            'id',           // Foreign key on the Country table (assuming 'id' is the primary key)
            'id',           // Local key on the ShippingZone table
            'country_id'    // Local key on the ShippingSubZone table that connects to the Country
        );
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }
}
