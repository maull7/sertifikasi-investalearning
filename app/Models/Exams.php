<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exams extends Model
{
    use HasFactory;
    protected $fillable = [
        'package_id',
        'title',
        'description',
        'duration',
        'passing_grade',
    ];
    
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

}
