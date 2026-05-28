<?php

namespace App\Models;

use Database\Factories\CommitteeFactory;
use Domain\Attachments\Models\Attachment;
use Domain\Certifications\Models\Certification;
use Domain\Certifications\Models\CertificationSlot;
use Domain\Federations\Models\Federation;
use Domain\Individuals\Models\ProfessionalRole;
use Domain\Licenses\Models\License;
use Domain\Memberships\Models\MembershipPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @method static create(array $committee)
 * @method static select(string ...$column)
 * @method static orderBy(string $column)
 */
class Committee extends Model
{
    use HasFactory;

    protected $table = 'committee';

    public $timestamps = false;

    protected $fillable = ['code', 'name', 'is_international'];

    protected $casts = [
        'is_international' => 'boolean',
    ];

    protected static function newFactory(): CommitteeFactory
    {
        return CommitteeFactory::new();
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function membershipPlans(): HasMany
    {
        return $this->hasMany(MembershipPlan::class);
    }

    public function certificationsSlots(): HasManyThrough
    {
        return $this->hasManyThrough(CertificationSlot::class, Certification::class);
    }

    public function professionalRoles(): HasMany
    {
        return $this->hasMany(ProfessionalRole::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function getIndividualDisplayName(): string
    {
        return match ($this->code) {
            'SPORT' => 'Underwater Sports',
            'DIVING' => 'CMAS Diving',
            'DIVINGSERVICES' => 'Diving Services',
            'SCIENTIFIC' => 'Environment & Science',
            default => $this->name
        };
    }

    /**
     * Federations that can manage this committee.
     */
    public function federations(): BelongsToMany
    {
        return $this->belongsToMany(Federation::class, 'federation_committee')
            ->withTimestamps();
    }

    /**
     * Check if this committee handles international (CMAS) certifications/licenses.
     */
    public function isInternational(): bool
    {
        return $this->is_international === true;
    }

    /**
     * Scope to only include international committees (DIVING, SCIENTIFIC).
     */
    public function scopeInternational(Builder $query): Builder
    {
        return $query->where('is_international', true);
    }

    /**
     * Scope to only include national (non-international) committees (SPORT, DIVINGSERVICES).
     */
    public function scopeNational(Builder $query): Builder
    {
        return $query->where('is_international', false);
    }

    public function getLogoPath(): string
    {
        if ($this->isInternational()) {
            $internationalLogo = config('branding.international.logo_path', 'img/international-logo.svg');

            return file_exists(public_path($internationalLogo))
                ? $internationalLogo
                : config('branding.primary.logo_path', 'img/project-logo.svg');
        }

        return config('branding.primary.logo_path', 'img/project-logo.svg');
    }

    /**
     * Get the website URL for this committee.
     */
    public function getWebsiteUrl(): string
    {
        return $this->isInternational()
            ? config('branding.international.website_label', 'international.example.test')
            : config('branding.primary.website_label', 'example.test');
    }

    /**
     * Get the website URL parts for styled display (blade views).
     */
    public function getWebsiteUrlParts(): array
    {
        $host = $this->getWebsiteUrl();
        $prefix = str_starts_with($host, 'www.') ? 'www.' : '';
        $hostWithoutPrefix = str_starts_with($host, 'www.') ? substr($host, 4) : $host;
        $lastDotPosition = strrpos($hostWithoutPrefix, '.');

        if ($lastDotPosition === false) {
            return ['prefix' => $prefix, 'domain' => $hostWithoutPrefix, 'suffix' => ''];
        }

        return [
            'prefix' => $prefix,
            'domain' => substr($hostWithoutPrefix, 0, $lastDotPosition),
            'suffix' => substr($hostWithoutPrefix, $lastDotPosition),
        ];
    }
}
