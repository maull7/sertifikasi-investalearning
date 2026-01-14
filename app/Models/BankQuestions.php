<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class BankQuestions extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_id',
        'question_type',
        'question',
        'solution',
        'explanation',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'answer',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(MasterTypes::class, 'type_id');
    }

    public function getQuestionImageUrlAttribute(): ?string
    {
        return $this->question_image ? asset('storage/' . $this->question_image) : null;
    }
}
