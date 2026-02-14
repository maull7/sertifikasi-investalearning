<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function userJoins(): HasMany
    {
        return $this->hasMany(UserJoin::class, 'id_package');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_joins', 'id_package', 'user_id')
            ->withTimestamps();
    }

    /**
     * Materi dalam paket via relasi: Package -> masterType -> subjects -> materials.
     * Materials tidak lagi punya id_package, hanya id_subject (subject -> material).
     */
    public function getMaterialsAttribute(): Collection
    {
        if (! $this->relationLoaded('masterType')) {
            $this->load(['masterType.subjects.materials' => fn ($q) => $q->with('subject')]);
        }
        $masterType = $this->masterType;
        if (! $masterType || ! $masterType->relationLoaded('subjects')) {
            return collect();
        }
        return $masterType->subjects->flatMap(fn ($subject) => $subject->materials);
    }

    /**
     * Kuis dalam paket via relasi: Package -> masterType -> subjects -> quizzes.
     * Sama seperti materi, kuis tidak punya id_package; tampil per mata pelajaran (subject).
     */
    public function getQuizzesAttribute(): Collection
    {
        if (! $this->relationLoaded('masterType')) {
            $this->load(['masterType.subjects.quizzes' => fn ($q) => $q->with('subject')]);
        }
        $masterType = $this->masterType;
        if (! $masterType || ! $masterType->relationLoaded('subjects')) {
            return collect();
        }
        return $masterType->subjects->flatMap(fn ($subject) => $subject->quizzes);
    }
}
