<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'title',
        'description',
        'duration',
        'passing_grade',
        'total_questions',
        'type',
        'show_result_after',
    ];

    protected function casts(): array
    {
        return [
            'show_result_after' => 'boolean',
        ];
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function mappingQuestions(): HasMany
    {
        return $this->hasMany(MappingQuestion::class, 'id_exam');
    }

    /** Mata pelajaran dari mapping paket (bisa lebih dari satu). */
    public function subjects(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'exam_subject', 'exam_id', 'subject_id')
            ->withTimestamps();
    }
}
