<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailResults extends Model
{
    protected $table = 'detail_results';
    protected $fillable = [
        'id_trans_question',
        'id_question',
        'user_answer',
        'correct_answer',
        'score_obtained',
    ];
}
