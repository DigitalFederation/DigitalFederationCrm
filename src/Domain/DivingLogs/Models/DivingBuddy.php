<?php

namespace Domain\DivingLogs\Models;

use App\Traits\CreatedUpdatedBy;
use Database\Factories\DivingBuddiesFactory;
use Domain\Individuals\Models\Individual;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DivingBuddy extends Model
{
    use CreatedUpdatedBy;
    use HasFactory;

    protected $fillable = ['user_id', 'individual_id', 'name', 'cmas_code'];

    protected static function newFactory(): DivingBuddiesFactory
    {
        return DivingBuddiesFactory::new();
    }

    // belongs to a individual
    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }

    // hasMany diving logs
    public function diving_logs(): BelongsToMany
    {
        return $this->belongsToMany(DivingLog::class, 'diving_log_buddies', 'diving_buddy_id', 'diving_log_id');
    }
}
