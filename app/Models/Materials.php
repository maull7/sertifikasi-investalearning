<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Materials extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'title',
        'description',
        'value',
        'file_name',
        'file_type',
        'file_size',
        'id_subject',
        'topic',
        'materi_type',
    ];

    protected $appends = [
        'file_icon',
        'file_size_formatted',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'id_subject');
    }

    public function getFileIconAttribute(): string
    {
        return match (strtolower((string) $this->file_type)) {
            'pdf' => 'ti ti-file-type-pdf',
            'doc', 'docx' => 'ti ti-file-type-doc',
            default => 'ti ti-file-description',
        };
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) {
            return '-';
        }

        $size = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return number_format($size, 2) . ' ' . $units[$i];
    }
}
