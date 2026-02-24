<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'id_master_types',
        'description',
        'status',
    ];

    public function masterType(): BelongsTo
    {
        return $this->belongsTo(MasterType::class, 'id_master_types');
    }

    /** Mapel yang di-mapping ke paket ini (isi paket = mapel apa saja). */
    public function mappedSubjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'package_subject', 'package_id', 'subject_id')
            ->withTimestamps();
    }

    public function getSubjectsForPackage(): Collection
    {
        return $this->mappedSubjects()->get();
    }

    public function userJoins(): HasMany
    {
        return $this->hasMany(UserJoin::class, 'id_package');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_joins', 'id_package', 'user_id')
            ->withTimestamps();
    }

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'package_staff', 'package_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Materi dalam paket: dari mappedSubjects (mapping mapel) atau fallback masterType->subjects.
     */
    public function getMaterialsAttribute(): Collection
    {
        $subjects = $this->getSubjectsForPackage();
        if ($subjects->isEmpty()) {
            return collect();
        }
        $subjectIds = $subjects->pluck('id');

        return Material::whereIn('id_subject', $subjectIds)->with('subject')->get();
    }

    /**
     * Kuis dalam paket: dari mappedSubjects (mapping mapel) atau fallback masterType->subjects.
     */
    public function getQuizzesAttribute(): Collection
    {
        $subjects = $this->getSubjectsForPackage();
        if ($subjects->isEmpty()) {
            return collect();
        }
        $subjectIds = $subjects->pluck('id');

        return Quiz::whereIn('subject_id', $subjectIds)->with('subject')->get();
    }
}
