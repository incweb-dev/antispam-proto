<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Fingerprint extends Model
{
    protected $fillable = [
        'project_id',
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

    protected $casts = [
        'webdriver' => 'boolean',
        'time_to_submit' => 'integer',
    ];

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }
}
