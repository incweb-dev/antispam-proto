<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'fingerprint_id',
        'phone',
        'name',
        'spam_score',
    ];

    public function fingerprint(){
        return $this->hasOne(Fingerprint::class);
    }
}
