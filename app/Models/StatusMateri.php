<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusMateri extends Model
{
    protected $table = 'status_materis';

    protected $fillable = [
        'id_user',
        'id_material',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'id_material');
    }
}
