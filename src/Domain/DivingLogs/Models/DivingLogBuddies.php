<?php

namespace Domain\DivingLogs\Models;

use Database\Factories\DivingLogBuddiesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivingLogBuddies extends Model
{
    use HasFactory;

    protected $table = 'diving_log_buddies';

    protected static function newFactory(): DivingLogBuddiesFactory
    {
        return DivingLogBuddiesFactory::new();
    }
}
