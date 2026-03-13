<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'front_background_path',
        'left_signature_image_path',
        'left_signature_name',
        'left_signature_title',
        'right_signature_image_path',
        'right_signature_name',
        'right_signature_title',
        'schema_title',
        'schema_description',
        'uk_list',
        'facilitator_list',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}

