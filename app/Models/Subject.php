<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'master_type_id',
        'name',
        'code',
        'description',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(MasterType::class, 'master_type_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'id_subject');
    }
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'subject_id');
    }
    public function statusMateris(): HasMany
    {
        return $this->hasMany(StatusMateri::class, 'id_subject');
    }
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'subject_id');
    }
}
