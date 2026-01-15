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
        return $this->hasMany(Materials::class, 'package_id');
    }

    public function masterType(): BelongsTo
    {
        return $this->belongsTo(MasterTypes::class, 'id_master_types');
    }
}
