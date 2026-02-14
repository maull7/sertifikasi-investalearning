<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailResultQuiz extends Model
{
    protected $table = 'detail_result_quiz';

    protected $fillable = [
        'id_trans_quiz',
        'id_question',
        'user_answer',
        'correct_answer',
        'score_obtained',
    ];

    protected function casts(): array
    {
        return [
            'score_obtained' => 'float',
        ];
    }

    public function transQuiz(): BelongsTo
    {
        return $this->belongsTo(TransQuiz::class, 'id_trans_quiz');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(BankQuestion::class, 'id_question');
    }
}
