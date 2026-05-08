<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaceToFaceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'title',
        'room_name',
        'zoom_join_url',
        'zoom_meeting_id',
        'zoom_passcode',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(FaceToFaceSession::class, 'schedule_id')->orderBy('session_date')->orderBy('start_time');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(FaceToFaceScheduleRegistration::class, 'schedule_id');
    }
}
