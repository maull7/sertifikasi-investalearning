<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransQuestions extends Model
{
    protected $table = 'trans_questions';
    protected $fillable = [
        'id_user',
        'id_package',
        'user_id',
        'id_question',
        'questions_answered',
        'total_questions',
        'total_score',
        'status',
    ];
    public function detailResults()
    {
        return $this->hasMany(DetailResults::class, 'id_trans_question');
    }
}
