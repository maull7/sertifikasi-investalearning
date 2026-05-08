<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserJoin extends Model
{
    protected $table = 'user_joins';

    protected $fillable = [
        'user_id',
        'id_package',
        'schedule_id',
        'status',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'id_package');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(FaceToFaceSchedule::class, 'schedule_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'user_join_id');
    }
}
