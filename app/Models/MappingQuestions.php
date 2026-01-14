<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MappingQuestions extends Model
{
    protected $table = 'mapping_questions';

    protected $fillable = [
        'id_question_bank',
        'id_exam',
    ];
    public function questionBank(): BelongsTo
    {
        return $this->belongsTo(BankQuestions::class, 'id_question_bank');
    }
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exams::class, 'id_exam');
    }
}
