<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MappingQuestion extends Model
{
    protected $table = 'mapping_questions';

    protected $fillable = [
        'id_question_bank',
        'id_exam',
    ];

    public function questionBank(): BelongsTo
    {
        return $this->belongsTo(BankQuestion::class, 'id_question_bank');
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'id_exam');
    }
}
