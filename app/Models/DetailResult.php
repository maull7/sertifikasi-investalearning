<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailResult extends Model
{
    protected $table = 'detail_results';
    protected $fillable = [
        'id_trans_question',
        'id_question',
        'user_answer',
        'correct_answer',
        'score_obtained',
    ];

    public function Question()
    {
        return $this->belongsTo(BankQuestion::class, 'id_question');
    }

    public function TransQuestion()
    {
        return $this->belongsTo(TransQuestion::class, 'id_trans_question');
    }
}
