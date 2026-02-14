<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransQuestion extends Model
{
    protected $table = 'trans_questions';
    protected $fillable = [
        'id_user',
        'id_package',
        'id_exam',
        'questions_answered',
        'total_questions',
        'total_score',
        'status',
    ];

    public function detailResults()
    {
        return $this->hasMany(DetailResult::class, 'id_trans_question');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'id_package');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'id_exam');
    }
}
