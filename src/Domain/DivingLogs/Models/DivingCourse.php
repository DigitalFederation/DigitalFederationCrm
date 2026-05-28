<?php

declare(strict_types=1);

namespace Domain\DivingLogs\Models;

use Domain\Certifications\Models\Certification;
use Domain\Entities\Models\Entity;
use Domain\Geographic\Models\District;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DivingCourse extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'entity_diving_courses';

    public const CERTIFICATION_SYSTEMS = [
        'CMAS' => 'CMAS',
        'SSI' => 'SSI',
        'SDI/TDI' => 'SDI/TDI',
        'PADI' => 'PADI',
        'GUE' => 'GUE',
        'DDI' => 'DDI',
        'NAUI' => 'NAUI',
    ];

    protected $fillable = [
        'entity_id',
        'name',
        'certification_system',
        'district_id',
        'location',
        'certification_id',
        'start_date',
        'about',
    ];

    protected $casts = [
        'start_date' => 'date',
    ];

    // Relationships
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function certification(): BelongsTo
    {
        return $this->belongsTo(Certification::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the display name for the course.
     * Prefers the custom name, falls back to certification name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? $this->certification?->name ?? '';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('course-image')
            ->useDisk('public')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('card')
            ->width(400)
            ->height(300)
            ->sharpen(10)
            ->nonQueued();
    }
}
