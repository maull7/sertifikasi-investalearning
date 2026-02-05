<?php

namespace App\Models;

use App\Models\MappingQuestion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = [
        'package_id',
        'title',
        'description',
        'duration',
        'passing_grade',
        'total_questions',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function mappingQuestions(): HasMany
    {
        return $this->hasMany(MappingQuestion::class, 'id_quiz');
    }
}
