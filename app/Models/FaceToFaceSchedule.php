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
        'teacher_id',
        'subject_id',
        'title',
        'schedule_date',
        'start_time',
        'end_time',
        'room_name',
        'zoom_join_url',
        'zoom_meeting_id',
        'zoom_passcode',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'schedule_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(FaceToFaceScheduleRegistration::class, 'schedule_id');
    }
}
