<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailCertificate extends Model
{
    protected $table = 'detail_certificates';

    protected $fillable = [
        'id_certificate',
        'id_teacher',
    ];

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class, 'id_certificate');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'id_teacher');
    }
}
