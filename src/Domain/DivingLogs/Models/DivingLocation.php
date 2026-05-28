<?php

namespace Domain\DivingLogs\Models;

use App\Models\Country;
use App\Models\User;
use App\Traits\CreatedUpdatedBy;
use Database\Factories\DivingLocationFactory;
use Domain\Entities\Models\Entity;
use Domain\Federations\Models\Federation;
use Domain\Geographic\Models\District;
use Domain\Individuals\Models\Individual;
use Domain\Users\Actions\GetUserTypeAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DivingLocation extends Model implements HasMedia
{
    use CreatedUpdatedBy;
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $table = 'diving_location';

    public const LEVELS = [
        'Beginner' => 'Beginner',
        'Intermediate' => 'Intermediate',
        'Advanced' => 'Advanced',
        'Technical' => 'Technical',
    ];

    public const DIVE_TYPES = [
        'Open Water' => 'Open Water',
        'Inland Waters' => 'Inland Waters',
        'Wall or Canyon' => 'Wall or Canyon',
        'Grotto' => 'Grotto',
        'Cave' => 'Cave',
        'Wreck' => 'Wreck',
        'Pool' => 'Pool',
    ];

    protected $fillable = ['name', 'region', 'country_id', 'district_id', 'lat', 'lng', 'native_name', 'owner_type', 'owner_id', 'depth', 'water_type', 'dive_type', 'level', 'notes'];

    protected function casts(): array
    {
        return [
            'level' => 'array',
            'dive_type' => 'array',
        ];
    }

    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->setOwnerAttribute();
        });
    }

    protected static function newFactory(): DivingLocationFactory
    {
        return DivingLocationFactory::new();
    }

    public function divingLogs(): HasMany
    {
        return $this->hasMany(DivingLog::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function owner(): MorphTo
    {
        return $this->morphTo('owner', 'owner_type', 'owner_id', 'id');
    }

    public function setOwnerAttribute(): void
    {
        switch (Auth::user()->group()->first()->code) {
            case 'INDIVIDUAL':
                $this->owner_type = Individual::class;
                $this->owner_id = Auth::user()->individuals()->first()->id;
                break;
            case 'ENTITY':
                $this->owner_type = Entity::class;
                $this->owner_id = Auth::user()->entities()->first()->id;
                break;
            case 'FEDERATION':
                $this->owner_type = Federation::class;
                $this->owner_id = Auth::user()->federations()->first()->id;
                break;
            case 'CMAS':
                $this->owner_type = User::class;
                $this->owner_id = Auth::user()->id;
                break;
        }
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('diving-location-images')
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile(); // Allow only one image per location for now
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->sharpen(10);

        $this->addMediaConversion('preview')
            ->width(400)
            ->height(400)
            ->sharpen(10);
    }

    public function scopeIndividualSearch(Builder $query): Builder
    {
        return $query
            ->whereHas('owner', function (Builder $query) {
                return $query
                    ->where('id', GetUserTypeAction::execute(Auth::user())->id)
                    ->orWhereIn('id', GetUserTypeAction::execute(Auth::user())->federations->pluck('federation.id')->toArray())
                    ->orWhereIn('id', GetUserTypeAction::execute(Auth::user())->entities->pluck('entity.id')->toArray());
            });
    }

    public function featuringEntities(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class, 'entity_featured_diving_location', 'diving_location_id', 'entity_id');
    }
}
