<?php

namespace Domain\DivingLogs\Models;

use App\Enums\DivingLogDiveTypeEnum;
use App\Models\Scopes\DivingLogSearchScope;
use Database\Factories\DivingLogFactory;
use Domain\DivingLogs\States\ApprovedDivingLogState;
use Domain\DivingLogs\States\DivingLogState;
use Domain\DivingLogs\States\DraftDivingLogState;
use Domain\DivingLogs\States\PendingDivingLogState;
use Domain\Individuals\Models\Individual;
use Domain\Individuals\Models\IndividualSequenceLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property class-string<DivingLogState>|null $status_class
 * @property DivingLogState $state
 */
class DivingLog extends Model
{
    use HasFactory;

    protected $table = 'diving_log';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dive_type' => DivingLogDiveTypeEnum::class,
    ];

    protected $guarded = ['id'];

    public static function booted()
    {
        static::creating(function ($model) {
            $individualLog = IndividualSequenceLog::firstOrCreate(
                ['individual_id' => $model->individual_id],
                ['log_number' => 0]
            );
            $individualLog->increment('log_number');
        });

        static::addGlobalScope(new DivingLogSearchScope);
    }

    // hasMany buddies
    public function buddies(): BelongsToMany
    {
        return $this->belongsToMany(DivingBuddy::class, 'diving_log_buddies', 'diving_log_id', 'diving_buddy_id');
    }

    protected static function newFactory(): DivingLogFactory
    {
        return DivingLogFactory::new();
    }

    /**
     * belongsTo DivingLocation
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(DivingLocation::class, 'diving_location_id');
    }

    public function diving(): HasOne
    {
        return $this->HasOne(DivingLogDiving::class);
    }

    public function extendedRange(): HasOne
    {
        return $this->HasOne(DivingLogExtendedRange::class);
    }

    public function freeDiving(): HasOne
    {
        return $this->HasOne(DivingLogFreediving::class);
    }

    public function rebreatherCCR(): HasOne
    {
        return $this->HasOne(DivingLogRebreatherCcr::class);
    }

    public function rebreatherSCR(): HasOne
    {
        return $this->HasOne(DivingLogRebreatherScr::class);
    }

    public function sequence()
    {
        return $this->hasOne(DivingLogIndividualSequence::class);
    }

    /**
     * Get the details for the specific dive type.
     *
     * This method returns an associative array that contains the type of the dive and the respective details.
     * The 'type' key in the returned array corresponds to the type of the dive. The type is one of the
     * constants in DivingLogDiveTypeEnum.
     * The 'details' key in the returned array corresponds to the details of the dive. The details are an instance
     * of the model associated with the dive type.
     *
     * Usage:
     * 1. To get the dive type:
     *    $divingLog->dive_details['type']
     *
     * 2. To get the dive details:
     *    $divingLog->dive_details['details']
     *
     * @return array The array containing the type of the dive and the respective details.
     */
    public function getDiveDetailsAttribute(): array
    {
        $diveDetails = match ($this->dive_type) {
            DivingLogDiveTypeEnum::ExtendedRange => $this->extendedRange,
            DivingLogDiveTypeEnum::Diving => $this->diving,
            DivingLogDiveTypeEnum::Freediving => $this->freeDiving,
            DivingLogDiveTypeEnum::RebreatherScr => $this->rebreatherSCR,
            DivingLogDiveTypeEnum::RebreatherCcr => $this->rebreatherCCR,
            default => null,
        };

        return ['type' => $this->dive_type, 'details' => $diveDetails];
    }

    public function loadDiveTypeRelation(): self
    {
        switch ($this->dive_type) {
            case DivingLogDiveTypeEnum::Diving:
                $this->load('diving');
                break;
            case DivingLogDiveTypeEnum::ExtendedRange:
                $this->load('extendedRange');
                break;
            case DivingLogDiveTypeEnum::Freediving:
                $this->load('freeDiving');
                break;
            case DivingLogDiveTypeEnum::RebreatherScr:
                $this->load('rebreatherSCR');
                break;
            case DivingLogDiveTypeEnum::RebreatherCcr:
                $this->load('rebreatherCCR');
                break;
        }

        return $this;
    }

    public function getStateAttribute(): DivingLogState
    {
        return new $this->status_class($this);
    }

    public function setStateAttribute($value): void
    {
        match ($value) {
            'pending' => $this->status_class = PendingDivingLogState::class,
            'approved' => $this->status_class = ApprovedDivingLogState::class,
            default => $this->status_class = DraftDivingLogState::class
        };
    }

    public function stateName(): string
    {
        return $this->state->name();
    }

    public function isDraft(): bool
    {
        return $this->state->isDraft();
    }

    public function isApproved(): bool
    {
        return $this->state->isApproved();
    }

    public function colorState()
    {
        return $this->state->color();
    }

    public function svgState()
    {
        return $this->state->svg();
    }

    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }

    public function validation(): HasMany
    {
        return $this->hasMany(DivingLogValidation::class);
    }

    public function scopeFilterType(Builder $query, int $type): Builder
    {
        return $query->where('dive_type', $type);
    }

    public function scopeFilterCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }
}
