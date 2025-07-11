<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Fingerprint extends Model
{
    protected $fillable = [
        'visitor_hash',
        'local_id',
        'ip',
        'user_agent',
        'language',
        'platform',
        'screen',
        'color_depth',
        'pixel_ratio',
        'timezone',
        'referrer',
        'connection_type',
        'memory',
        'cores',
        'webdriver',
        'time_to_submit',
    ];

    public function order(): HasOne {
        return $this->hasOne(Order::class);
    }
}
