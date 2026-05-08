<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = ['user_join_id', 'proof_image', 'status', 'note'];

    public function userJoin(): BelongsTo
    {
        return $this->belongsTo(UserJoin::class, 'user_join_id');
    }
}
