<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTypes extends Model
{
    use HasFactory;
    protected $table = 'master_types';

    protected $fillable = [
        'name_type',

    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'master_type_id');
    }
    public function packages()
    {
        return $this->hasMany(Package::class, 'id_master_types');
    }
}
