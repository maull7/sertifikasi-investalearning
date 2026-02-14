<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransQuiz extends Model
{
    protected $table = 'trans_quiz';

    protected $fillable = [
        'user_id',
        'quiz_id',
        'package_id',
        'questions_answered',
        'total_questions',
        'total_score',
        'status',
        'attempted_count',
    ];

    protected function casts(): array
    {
        return [
            'total_score' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function detailResultQuizzes(): HasMany
    {
        return $this->hasMany(DetailResultQuiz::class, 'id_trans_quiz');
    }
}
