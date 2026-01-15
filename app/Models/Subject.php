<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->belongsTo(MasterTypes::class, 'master_type_id');
    }
}


