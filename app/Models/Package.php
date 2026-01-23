<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'id_master_types',
        'description',
        'status',
    ];
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'package_id');
    }

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
}
