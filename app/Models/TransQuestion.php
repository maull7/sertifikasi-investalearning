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
        'id_type',
        'questions_answered',
        'total_questions',
        'total_score',
        'status',
    ];
    public function detailResults()
    {
        return $this->hasMany(DetailResult::class, 'id_trans_question');
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function Package()
    {
        return $this->belongsTo(Package::class, 'id_package');
    }

    public function Exam()
    {
        return $this->belongsTo(Exam::class, 'id_exam');
    }
    public function Type()
    {
        return $this->belongsTo(MasterType::class, 'id_type');
    }
}
