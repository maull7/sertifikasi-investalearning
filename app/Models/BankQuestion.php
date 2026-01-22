<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankQuestion extends Model
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
        return $this->belongsTo(MasterType::class, 'type_id');
    }

    public function getQuestionImageUrlAttribute(): ?string
    {
        if (($this->question_type ?? 'Text') !== 'Image') {
            return null;
        }

        return $this->question ? asset('storage/' . ltrim($this->question, '/')) : null;
    }
    public function MappingQuestions(): HasMany
    {
        return $this->hasMany(MappingQuestion::class, 'id_question_bank');
    }
}
