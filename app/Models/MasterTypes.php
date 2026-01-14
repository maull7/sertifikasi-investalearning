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
}
