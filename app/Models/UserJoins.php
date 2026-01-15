<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserJoins extends Model
{
    protected $table = 'user_joins';
    
    protected $fillable = [
        'user_id',
        'id_package',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'id_package');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
