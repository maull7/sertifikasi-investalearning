<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\TransQuestions;
use App\Http\Controllers\Controller;
use App\Models\DetailResults;
use Illuminate\Support\Facades\Auth;


class HistoryExamController extends Controller
{
    public function index()
    {
        $history = TransQuestions::with('Package', 'Exam', 'Type')
            ->where('id_user', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('user.history.index', compact('history'));
    }
    public function detail($id)
    {
        $historyDetail = DetailResults::with('Question', 'TransQuestion')
            ->where('id_trans_question', $id)
            ->paginate(10);
        return view('user.history.detail', compact('historyDetail'));
    }
}
