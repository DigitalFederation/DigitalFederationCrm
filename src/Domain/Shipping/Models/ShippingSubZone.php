<?php

namespace Domain\Shipping\Models;

use App\Models\Country;
use Database\Factories\ShippingSubZoneFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ShippingSubZone extends Model
{
    use HasFactory;

    protected $table = 'shipping_sub_zones';

    protected $fillable = ['name', 'country_id'];

    protected static function newFactory(): ShippingSubZoneFactory
    {
        return ShippingSubZoneFactory::new();
    }

    /**
     * Get the zone that the sub-zone belongs to.
     */
    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function zones(): BelongsToMany
    {
        return $this->belongsToMany(ShippingZone::class, 'shipping_zone_sub_zone', 'sub_zone_id', 'zone_id');
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }
}
