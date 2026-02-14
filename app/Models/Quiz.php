<?php

namespace App\Models;

use App\Models\MappingQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_id',
        'title',
        'description',
        'duration',
        'passing_grade',
        'total_questions',
    ];

    public function mappingQuestions(): HasMany
    {
        return $this->hasMany(MappingQuestion::class, 'id_quiz');
    }
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
