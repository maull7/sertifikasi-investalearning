<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Utils\Utils;
use App\Models\DetailResult;
use App\Models\Exam;
use App\Models\Package;
use App\Models\TransQuestion;
use App\Models\TransQuiz;
use App\Models\UserJoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HistoryExamController extends Controller
{
    private $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }


    public function index(Request $request)
    {
        $packages = Package::select('id', 'title')->orderBy('title')->get();
        $exams = Exam::select('id', 'title', 'type')->orderBy('title')->get();
        $packageId = $request->get('package_id');
        $examId = $request->get('exam_id');
        $examType = $request->get('exam_type', 'posttest'); // tab: pretest | posttest

        $history = TransQuestion::with(['package:id,title,id_master_types', 'package.masterType:id,name_type', 'exam:id,title,type'])
            ->whereNotNull('id_exam')
            ->whereHas('Exam', fn($q) => $q->where('type', $examType))
            ->when($packageId, fn($query) => $query->where('id_package', $packageId))
            ->when($examId, fn($query) => $query->where('id_exam', $examId))
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        $packageApprove = UserJoin::with('package', 'user')
            ->where('user_id', Auth::id())
            ->where('status', 'approved')
            ->paginate(10);
        return view('user.history.index', compact('history', 'packages', 'exams', 'packageId', 'examId', 'examType', 'packageApprove'));
    }

    public function detail($id)
    {
        $historyDetail = DetailResult::with(['Question.subject', 'TransQuestion.exam', 'TransQuestion.package'])
            ->where('id_trans_question', $id)
            ->paginate(10);

        return view('user.history.detail', compact('historyDetail'));
    }
    public function show(Request $request, UserJoin $userJoin): View
    {
        if ($userJoin->status !== 'approved') {
            abort(404);
        }
        $userJoin->load(['user', 'package']);
        $user = $userJoin->user;
        $package = $userJoin->package;
        $subjects = $package->getSubjectsForPackage();
        $subjects->load('quizzes');

        $subjectStats = [];
        foreach ($subjects as $subject) {
            $quizIds = $subject->quizzes->pluck('id')->all();
            $lastQuiz = null;
            if (! empty($quizIds)) {
                $lastQuiz = TransQuiz::where('user_id', $user->id)
                    ->where('package_id', $package->id)
                    ->whereIn('quiz_id', $quizIds)
                    ->orderByDesc('created_at')
                    ->first();
            }
            $subjectStats[] = [
                'subject' => $subject,
                'last_quiz_score' => $lastQuiz?->total_score,
                'last_quiz_at' => $lastQuiz?->created_at,
            ];
        }

        $period = $request->query('period', 'all');
        $examType = $request->query('exam_type', '');
        $dateFrom = $this->utils->periodToDate($period);

        $exams = Exam::where('package_id', $package->id)->get();
        $tryoutQuery = TransQuestion::with('exam')
            ->where('id_user', $user->id)
            ->where('id_package', $package->id)
            ->when($dateFrom, fn($q) => $q->where('created_at', '>=', $dateFrom))
            ->when($examType !== '', fn($q) => $q->whereHas('exam', fn($eq) => $eq->where('type', $examType)));
        $tryoutHistoryAll = (clone $tryoutQuery)->orderByDesc('created_at')->get();
        $tryoutScores = [];
        foreach ($exams as $exam) {
            $last = (clone $tryoutQuery)->where('id_exam', $exam->id)->orderByDesc('created_at')->first();
            if ($last !== null) {
                $tryoutScores[] = (float) $last->total_score;
            }
        }
        $tryoutAverage = count($tryoutScores) > 0 ? array_sum($tryoutScores) / count($tryoutScores) : null;

        $tryoutHistory = (clone $tryoutQuery)->orderByDesc('created_at')->paginate(10, ['*'], 'tryout_page')->withQueryString();
        $tryoutChartData = $tryoutHistoryAll->map(fn($t) => [
            'label' => ($t->exam?->title ?? 'Tryout') . ' · ' . $t->created_at->format('d/m'),
            'score' => (float) $t->total_score,
            'date' => $t->created_at->format('Y-m-d H:i'),
        ])->values()->all();

        $quizQuery = TransQuiz::with('quiz.subject')
            ->where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->when($dateFrom, fn($q) => $q->where('created_at', '>=', $dateFrom));
        $quizHistoryAll = (clone $quizQuery)->orderByDesc('created_at')->get();
        $quizHistory = (clone $quizQuery)->orderByDesc('created_at')->paginate(10, ['*'], 'quiz_page')->withQueryString();
        $quizBySubject = $quizHistoryAll->groupBy(fn($t) => $t->quiz?->subject?->name ?? 'Lainnya');
        $quizChartData = $quizBySubject->map(fn($items) => $items->first())->map(fn($t) => [
            'label' => $t->quiz?->subject?->name ?? 'Kuis',
            'score' => (float) $t->total_score,
        ])->values()->all();

        return view('user.history.show', [
            'period' => $period,
            'examType' => $examType,
            'userJoin' => $userJoin,
            'user' => $user,
            'package' => $package,
            'subjectStats' => $subjectStats,
            'tryoutAverage' => $tryoutAverage,
            'tryoutCount' => count($tryoutScores),
            'tryoutHistory' => $tryoutHistory,
            'tryoutChartData' => $tryoutChartData,
            'quizHistory' => $quizHistory,
            'quizChartData' => $quizChartData,
        ]);
    }
    public function tryoutDetail(UserJoin $userJoin, TransQuestion $transQuestion): View
    {
        if ($userJoin->status !== 'approved') {
            abort(404);
        }
        if ((int) $transQuestion->id_user !== (int) $userJoin->user_id || (int) $transQuestion->id_package !== (int) $userJoin->id_package) {
            abort(404);
        }

        $transQuestion->load(['exam', 'package', 'user']);
        $detailResults = $transQuestion->detailResults()->with('Question')->orderBy('id')->get();

        return view('user.history.tryout-detail', [
            'userJoin' => $userJoin,
            'user' => $userJoin->user,
            'package' => $userJoin->package,
            'trans' => $transQuestion,
            'exam' => $transQuestion->exam,
            'detailResults' => $detailResults,
        ]);
    }
}
