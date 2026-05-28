<?php

namespace Domain\DivingLogs\Models;

use Domain\Individuals\Models\Individual;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivingLogIndividualSequence extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'individual_diving_log_sequence';

    protected $fillable = [
        'individual_id',
        'diving_log_id',
        'log_number',
        'dive_type',
        'initial_log_number',
    ];

    /**
     * Get the individual associated with the sequence.
     */
    public function individual()
    {
        return $this->belongsTo(Individual::class, 'individual_id');
    }

    /**
     * Get the diving log associated with the sequence.
     */
    public function divingLog()
    {
        return $this->belongsTo(DivingLog::class, 'diving_log_id');
    }
}
