<?php

namespace Domain\Products\Models;

use Database\Factories\ProductFactory;
use Domain\Documents\Models\DocumentDetail;
use Domain\Memberships\Models\MembershipPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @method static select(string ...$columns)
 */
class Product extends Model
{
    use HasFactory;

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    protected $fillable = ['name', 'description', 'price', 'tax_percentage', 'tax_value'];

    public function membershipPlans(): BelongsToMany
    {
        return $this->belongsToMany(MembershipPlan::class, 'product_membership_plans');
    }

    public function documentDetails(): MorphToMany
    {
        return $this->morphedByMany(DocumentDetail::class, 'owner');
    }

    public function scopeName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', "%$name%");
    }
}
