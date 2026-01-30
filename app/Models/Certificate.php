<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Certificate extends Model
{
    protected $table = 'certificates';

    protected $fillable = [
        'id_user',
        'id_package',
        'id_master_type',
        'certificate_number',
        'training_date_start',
        'training_date_end',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'id_package');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(MasterType::class, 'id_master_type');
    }

    public function detailCertificates(): HasMany
    {
        return $this->hasMany(DetailCertificate::class, 'id_certificate');
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'detail_certificates', 'id_certificate', 'id_teacher');
    }
}
