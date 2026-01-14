<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exams extends Model
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
        return $this->hasMany(MappingQuestions::class, 'id_exam');
    }
}
