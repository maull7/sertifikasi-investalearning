<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceToFaceScheduleRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'user_id',
        'participant_email',
        'zoom_registrant_id',
        'zoom_join_url',
        'invitation_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'invitation_sent_at' => 'datetime',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(FaceToFaceSchedule::class, 'schedule_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
