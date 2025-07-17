<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    public const TOKEN_LENGTH = 5;

    protected $fillable = [
        'name',
        'token',
        'fingerprints_count',
    ];

    protected $casts = [
        'fingerprints_count' => 'integer',
    ];

    public function fingerprints(): HasMany
    {
        return $this->hasMany(Fingerprint::class);
    }
}
