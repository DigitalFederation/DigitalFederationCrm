<?php

namespace Domain\Federations\Models;

use App\Models\Committee;
use App\Models\Country;
use App\Models\User;
use App\Packages\MantaRayLms\Domain\Courses\Models\Course;
use App\Packages\MantaRayLms\Domain\Courses\Models\CourseSeat;
use App\Packages\MantaRayLms\Domain\Courses\Models\FederationAccess;
use App\Packages\MantaRayLms\Domain\Courses\Models\FederationCoursePurchase;
use App\Packages\MantaRayLms\Enums\PurchaseSeatStatusEnum;
use Database\Factories\FederationFactory;
use Domain\Certifications\Models\Certification;
use Domain\Certifications\Models\CertificationAttributed;
use Domain\Certifications\Models\CertificationSlot;
use Domain\Documents\Models\Document;
use Domain\Entities\Models\Entity;
use Domain\Entities\Models\EntityFederation;
use Domain\EvtEvents\Models\Organizer;
use Domain\Federations\Enums\SportOrClassAssociationCategory;
use Domain\Federations\Enums\TerritorialAssociationCategory;
use Domain\Geographic\Models\District;
use Domain\Geographic\Models\Zone;
use Domain\Individuals\Models\Individual;
use Domain\Individuals\Models\IndividualFederation;
use Domain\Licenses\Models\License;
use Domain\Licenses\Models\LicenseAttributed;
use Domain\Memberships\Models\LocalMembershipPlan;
use Domain\Memberships\Models\Membership;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Role;

/**
 * @method static create(array $federation)
 * @method static find($id)
 * @method static paginate(int $int)
 * @method static select(string ...$column)
 * @method static insert(array $federations)
 * @method static findOrFail(int $id)
 */
class Federation extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    protected static function newFactory(): FederationFactory
    {
        return FederationFactory::new();
    }

    protected $table = 'federation';

    protected static function booted(): void
    {
        // Users with role federation only can find own federations
        // static::addGlobalScope(new FederationCurrentScope);
    }

    protected $fillable = [
        'country_id',
        'district_id',
        'parent_id',
        'name',
        'is_local',
        'category',
        'is_manual',
        'legal_name',
        'address',
        'location',
        'zip_code',
        'lat',
        'lng',
        'website',
        'email',
        'phone',
        'code_cmas',
        'vat_number',
        'is_default_federation',
        'can_issue_certifications',
        'board_members',
    ];

    protected $casts = [
        'is_manual' => 'boolean',
        'is_default_federation' => 'boolean',
        'can_issue_certifications' => 'boolean',
        'board_members' => 'array',
    ];

    /**
     * The Collection component will show a preview thumbnail for items in the collection it is showing.
     * To generate that thumbnail, you must add a conversion like this one to your model.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('organization-event-hero')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            // ->fit(Manipulations::FIT_CROP, 150, 150)
            ->fit(Fit::Crop, 150, 150)
            ->nonQueued();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function zones(): BelongsToMany
    {
        return $this->belongsToMany(Zone::class, 'federation_zone')
            ->withTimestamps();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Federation::class, 'parent_id');
    }

    public function childs(): HasMany
    {
        return $this->hasMany(Federation::class, 'parent_id');
    }

    public function organizers()
    {
        return $this->morphMany(Organizer::class, 'organizable');
    }

    public function certificationsAttributed(): HasMany
    {
        return $this->hasMany(CertificationAttributed::class);
    }

    public function certificationSlots(): HasMany
    {
        return $this->hasMany(CertificationSlot::class);
    }

    public function licensesAttributed(): HasMany
    {
        return $this->hasMany(LicenseAttributed::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function individuals(): BelongsToMany
    {
        return $this->belongsToMany(Individual::class, 'individual_federation')
            ->withPivot('status_class');
    }

    public function individualFederations(): HasMany
    {
        return $this->HasMany(IndividualFederation::class);
    }

    public function entities(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class, 'entity_federation');
    }

    // Add this relationship to access the entity_federation table
    public function entityFederations(): HasMany
    {
        return $this->hasMany(EntityFederation::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'federation_user', 'federation_id', 'user_id');
    }

    public function activator(): MorphMany
    {
        return $this->morphMany(CertificationAttributed::class, 'activator');
    }

    // Method to get the display name of the federation
    public function getDisplayName(): string
    {
        return $this->code_cmas;
    }

    public function localMembershipPlan()
    {
        return $this->hasMany(LocalMembershipPlan::class, 'local_federation_id');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'owner');
    }

    /**
     * Get the voting rights records associated with the federation.
     */
    public function votingRights(): HasMany
    {
        return $this->hasMany(FederationVotingRight::class);
    }

    /**
     * Scope a query to only include results from date
     */
    public function scopeFilterName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeFilterCountry(Builder $query, int $country_id): Builder
    {
        return $query->where(compact('country_id'));
    }

    public function scopeFilterEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', 'like', '%' . $email . '%');
    }

    public function scopeFilterCode(Builder $query, string $code_cmas): Builder
    {
        return $query->where('code_cmas', 'like', '%' . $code_cmas . '%');
    }

    public function scopeFilterCommittee(Builder $query, int $committee_id): Builder
    {
        return $query->whereHas('memberships', function (Builder $query) use ($committee_id) {
            return $query->whereHas('plans', function (Builder $query) use ($committee_id) {
                return $query->where(compact('committee_id'));
            });
        });
    }

    public function scopeFilterZone(Builder $query, int $geo_zone_id): Builder
    {
        return $query->whereHas('country', function (Builder $query) use ($geo_zone_id) {
            return $query->where(compact('geo_zone_id'));
        });
    }

    public function scopeFilterRegion(Builder $query, int $sub_region_id): Builder
    {
        return $query->whereHas('country', function (Builder $query) use ($sub_region_id) {
            return $query->where(compact('sub_region_id'));
        });
    }

    public function scopeFilterIsLocal(Builder $query, bool $is_local): Builder
    {
        return $query->where(compact('is_local'));
    }

    public function scopeFilterMembershipPlan(Builder $query, int $membership_plan_id): Builder
    {
        return $query->whereHas('memberships', function ($q) use ($membership_plan_id) {
            $q->whereHas('plans', function ($subQ) use ($membership_plan_id) {
                $subQ->where('membership_plan.id', $membership_plan_id);
            });
        });
    }

    /**
     * LMS methods
     */
    public function coursePurchases()
    {
        return $this->hasMany(FederationCoursePurchase::class);
    }

    public function courseSeats()
    {
        return $this->hasMany(CourseSeat::class);
    }

    public function getAvailableSeatsForCourse($courseId)
    {
        $totalPurchased = $this->coursePurchases()
            ->where('course_id', $courseId)
            ->where('status', PurchaseSeatStatusEnum::APPROVED)
            ->sum('quantity_purchased');

        $totalAllocated = $this->courseSeats()
            ->where('course_id', $courseId)
            ->sum('quantity');

        return $totalPurchased - $totalAllocated;
    }

    public function allocateSeatsToEntity(Course $course, $entityId, int $quantity)
    {
        $availableSeats = $this->getAvailableSeatsForCourse($course->id);

        if ($quantity > $availableSeats) {
            throw new \Exception("Not enough seats available. Available: {$availableSeats}, Requested: {$quantity}");
        }

        return CourseSeat::updateOrCreate(
            [
                'course_id' => $course->id,
                'entity_id' => $entityId,
                'federation_id' => $this->id,
            ],
            [
                'quantity' => \DB::raw('quantity + ' . $quantity),
            ]
        );
    }

    public function lmsAccess(): HasOne
    {
        return $this->hasOne(FederationAccess::class, 'federation_id');
    }
    /**
     * Scope to federations that have active LMS access
     */
    public function scopeHasLmsAccess(Builder $query): Builder
    {
        return $query->whereHas('lmsAccess', function ($query) {
            $query->where('is_active', true);
        });
    }

    /**
     * Check if federation has active LMS access
     */
    public function hasActiveLmsAccess(): bool
    {
        return $this->lmsAccess()->where('is_active', true)->exists();
    }

    /**
     * Check if federation is a local federation
     */
    public function isLocal(): bool
    {
        return $this->is_local === true;
    }

    /**
     * Check if federation is a main federation (parent_id = null/0 and is_local = null/0)
     */
    public function isMainFederation(): bool
    {
        return (is_null($this->parent_id) || $this->parent_id == 0)
            && (is_null($this->is_local) || $this->is_local == 0);
    }

    /**
     * Check if federation can issue certifications
     */
    public function canIssueCertifications(): bool
    {
        return $this->can_issue_certifications === true;
    }

    public function isSportOrClassAssociation(): bool
    {
        return $this->category === SportOrClassAssociationCategory::class;
    }

    public function isTerritorialAssociation(): bool
    {
        return $this->category === TerritorialAssociationCategory::class;
    }

    public function scopeSportOrClassAssociations(Builder $query): Builder
    {
        return $query->where('category', SportOrClassAssociationCategory::class);
    }

    public function scopeNonManual(Builder $query): Builder
    {
        return $query->where('is_manual', false);
    }

    public function scopeAvailableForIndividualRequest(Builder $query): Builder
    {
        return $query->sportOrClassAssociations()->nonManual();
    }

    /**
     * Get all local federations belonging to this federation
     */
    public function localFederations(): HasMany
    {
        return $this->childs()->where('is_local', true);
    }

    public function scopeFilterByZone(Builder $query, int $zoneId): Builder
    {
        return $query->whereHas('zones', function ($q) use ($zoneId) {
            $q->where('zones.id', $zoneId);
        });
    }

    public function scopeFilterByDistrict(Builder $query, int $districtId): Builder
    {
        return $query->where('district_id', $districtId);
    }

    /**
     * Licenses that this federation can offer to its member entities.
     * Many-to-many relationship through federation_licenses pivot table.
     */
    public function licenses(): BelongsToMany
    {
        return $this->belongsToMany(License::class, 'federation_licenses')
            ->withTimestamps();
    }

    /**
     * Check if federation has permission to offer a specific license.
     */
    public function hasLicense(License $license): bool
    {
        return $this->licenses()->where('license_id', $license->id)->exists();
    }

    /**
     * Get licenses available for entities of this federation.
     * Filters to only return entity-type licenses.
     */
    public function availableLicensesForEntities(): Collection
    {
        return $this->licenses()
            ->hasLicenseType('entity')
            ->get();
    }

    /**
     * Roles that can be assigned within this federation.
     * Many-to-many relationship through federation_roles pivot table.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'federation_roles')
            ->withPivot('requires_active_membership')
            ->withTimestamps();
    }

    /**
     * Committees that this federation can manage.
     * Many-to-many relationship through federation_committee pivot table.
     */
    public function committees(): BelongsToMany
    {
        return $this->belongsToMany(Committee::class, 'federation_committee')
            ->withTimestamps();
    }

    /**
     * Check if federation can manage a specific committee.
     *
     * @param  int|Committee  $committee  Committee ID or Committee model
     */
    public function canManageCommittee(int|Committee $committee): bool
    {
        $committeeId = $committee instanceof Committee ? $committee->id : $committee;

        return $this->committees()->where('committee.id', $committeeId)->exists();
    }

    /**
     * Check if federation can manage a specific certification.
     * Federation must have access to the certification's committee.
     */
    public function canManageCertification(Certification $certification): bool
    {
        if (! $certification->committee_id) {
            return false;
        }

        return $this->canManageCommittee($certification->committee_id);
    }

    /**
     * Check if federation can manage a specific license.
     * Federation must have access to the license's committee.
     */
    public function canManageLicense(License $license): bool
    {
        if (! $license->committee_id) {
            return false;
        }

        return $this->canManageCommittee($license->committee_id);
    }

    /**
     * Get committee IDs that this federation can manage.
     */
    public function getCommitteeIds(): Collection
    {
        return $this->committees()->pluck('committee.id');
    }
}
