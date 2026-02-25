<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MonitorPackageExport;
use App\Exports\MonitorParticipantExport;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Package;
use App\Models\TransQuestion;
use App\Models\TransQuiz;
use App\Models\UserJoin;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ParticipantMonitorController extends Controller
{
    /**
     * Daftar paket dengan jumlah peserta (approved). Klik paket → daftar peserta.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $user = Auth::user();

        $packageIds = [];

        if ($user->role === 'Petugas') {
            $packageIds = $user->managedPackages()
                ->pluck('package_id')
                ->toArray();
        }

        $query = Package::with('masterType')
            ->withCount([
                'userJoins as participants_count' => fn ($q) => $q->where('status', 'approved'),
            ])
            ->having('participants_count', '>', 0)
            ->when(
                $search,
                fn ($q, $search) => $q->where('title', 'like', "%{$search}%")
            );

        // ⬇ FILTER DULU SEBELUM PAGINATE
        if ($user->role === 'Petugas') {
            $query->whereIn('id', $packageIds);
        }

        $packages = $query
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        return view('admin.monitor-participants.index', compact('packages', 'search'));
    }

    /**
     * Daftar peserta dalam satu paket + ranking nilai (rata-rata tryout).
     */
    public function participants(Request $request, Package $package): View
    {
        $search = $request->query('search');

        $userJoins = UserJoin::with('user')
            ->where('id_package', $package->id)
            ->where('status', 'approved')
            ->when($search, function ($q, $search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"));
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $exams = Exam::where('package_id', $package->id)->get();
        $ranked = $userJoins->map(function (UserJoin $uj) use ($exams) {
            $scores = [];
            foreach ($exams as $exam) {
                $last = TransQuestion::where('id_user', $uj->user_id)
                    ->where('id_package', $uj->id_package)
                    ->where('id_exam', $exam->id)
                    ->orderByDesc('created_at')
                    ->first();
                if ($last !== null) {
                    $scores[] = (float) $last->total_score;
                }
            }
            $avg = count($scores) > 0 ? array_sum($scores) / count($scores) : null;

            return ['user_join' => $uj, 'avg_tryout' => $avg];
        })->sortByDesc('avg_tryout')->values();

        $rank = 0;
        $rankedWithPosition = $ranked->map(function ($r) use (&$rank) {
            $rank++;
            $r['rank'] = $rank;

            return $r;
        });

        $participants = new \Illuminate\Pagination\LengthAwarePaginator(
            $rankedWithPosition->forPage($request->integer('page', 1), 15),
            $rankedWithPosition->count(),
            15,
            $request->integer('page', 1),
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $rankingChartData = $rankedWithPosition->map(fn ($r) => [
            'label' => $r['user_join']->user->name ?? '-',
            'score' => round($r['avg_tryout'] ?? 0, 1),
        ])->values()->all();

        $subjects = $package->mappedSubjects()->with('quizzes')->get();
        $userIds = $rankedWithPosition->pluck('user_join.user_id')->flatten()->unique()->all();
        $transQuizzes = TransQuiz::with('quiz')
            ->where('package_id', $package->id)
            ->whereIn('user_id', $userIds)
            ->get();
        $subjectChartData = $this->buildSubjectChartData($rankedWithPosition, $subjects, $transQuizzes);

        $package->load('masterType');

        return view('admin.monitor-participants.participants', [
            'package' => $package,
            'participants' => $participants,
            'search' => $search,
            'rankingChartData' => $rankingChartData,
            'subjectChartData' => $subjectChartData,
        ]);
    }

    /**
     * Detail monitor: nama peserta, daftar mapel dengan nilai terakhir kuis, rata-rata nilai tryout.
     */
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
        $dateFrom = $this->periodToDate($period);

        $exams = Exam::where('package_id', $package->id)->get();
        $tryoutQuery = TransQuestion::with('exam')
            ->where('id_user', $user->id)
            ->where('id_package', $package->id)
            ->when($dateFrom, fn ($q) => $q->where('created_at', '>=', $dateFrom))
            ->when($examType !== '', fn ($q) => $q->whereHas('exam', fn ($eq) => $eq->where('type', $examType)));
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
        $tryoutChartData = $tryoutHistoryAll->map(fn ($t) => [
            'label' => ($t->exam?->title ?? 'Tryout').' · '.$t->created_at->format('d/m'),
            'score' => (float) $t->total_score,
            'date' => $t->created_at->format('Y-m-d H:i'),
        ])->values()->all();

        $quizQuery = TransQuiz::with('quiz.subject')
            ->where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->when($dateFrom, fn ($q) => $q->where('created_at', '>=', $dateFrom));
        $quizHistoryAll = (clone $quizQuery)->orderByDesc('created_at')->get();
        $quizHistory = (clone $quizQuery)->orderByDesc('created_at')->paginate(10, ['*'], 'quiz_page')->withQueryString();
        $quizBySubject = $quizHistoryAll->groupBy(fn ($t) => $t->quiz?->subject?->name ?? 'Lainnya');
        $quizChartData = $quizBySubject->map(fn ($items) => $items->first())->map(fn ($t) => [
            'label' => $t->quiz?->subject?->name ?? 'Kuis',
            'score' => (float) $t->total_score,
        ])->values()->all();

        return view('admin.monitor-participants.show', [
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

    /**
     * @param  \Illuminate\Support\Collection<int, array{user_join: UserJoin, avg_tryout: float|null, rank: int}>  $rankedWithPosition
     * @param  \Illuminate\Support\Collection<int, \App\Models\Subject>  $subjects
     * @param  \Illuminate\Support\Collection<int, \App\Models\TransQuiz>  $transQuizzes
     * @return array{labels: array<int, string>, datasets: array<int, array{label: string, data: array<int, float|null>, backgroundColor: string}>}
     */
    private function buildSubjectChartData($rankedWithPosition, $subjects, $transQuizzes): array
    {
        $labels = $rankedWithPosition->map(fn ($r) => $r['user_join']->user->name ?? '-')->values()->all();
        $colors = ['rgb(99, 102, 241)', 'rgb(34, 197, 94)', 'rgb(234, 179, 8)', 'rgb(239, 68, 68)', 'rgb(168, 85, 247)', 'rgb(20, 184, 166)'];
        $datasets = [];
        $subjectIndex = 0;

        foreach ($subjects as $subject) {
            $quizIds = $subject->quizzes->pluck('id')->all();
            $data = [];
            foreach ($rankedWithPosition as $r) {
                $userId = $r['user_join']->user_id;
                $userQuizzes = $transQuizzes->filter(fn ($t) => (int) $t->user_id === (int) $userId && in_array((int) $t->quiz_id, array_map('intval', $quizIds), true));
                $avg = $userQuizzes->isEmpty() ? null : round($userQuizzes->avg('total_score'), 1);
                $data[] = $avg;
            }
            $color = $colors[$subjectIndex % count($colors)];
            $datasets[] = [
                'label' => $subject->name,
                'data' => $data,
                'backgroundColor' => $color,
            ];
            $subjectIndex++;
        }

        return ['labels' => $labels, 'datasets' => $datasets];
    }

    private function periodToDate(?string $period): ?Carbon
    {
        if ($period === 'all' || ! $period) {
            return null;
        }
        $now = Carbon::now();

        return match ($period) {
            '7d' => $now->copy()->subDays(7),
            '30d' => $now->copy()->subDays(30),
            '3m' => $now->copy()->subMonths(3),
            default => null,
        };
    }

    /**
     * Export laporan per paket (daftar peserta + ranking + rata tryout).
     */
    public function exportPackage(Package $package)
    {
        $package->load('masterType');
        $userJoins = UserJoin::with('user')
            ->where('id_package', $package->id)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        $exams = Exam::where('package_id', $package->id)->get();
        $ranked = $userJoins->map(function (UserJoin $uj) use ($exams) {
            $scores = [];
            foreach ($exams as $exam) {
                $last = TransQuestion::where('id_user', $uj->user_id)
                    ->where('id_package', $uj->id_package)
                    ->where('id_exam', $exam->id)
                    ->orderByDesc('created_at')
                    ->first();
                if ($last !== null) {
                    $scores[] = (float) $last->total_score;
                }
            }
            $avg = count($scores) > 0 ? array_sum($scores) / count($scores) : null;

            return ['user_join' => $uj, 'avg_tryout' => $avg];
        })->sortByDesc('avg_tryout')->values();

        $rank = 0;
        $rows = $ranked->map(function ($r) use (&$rank) {
            $rank++;

            return [
                $rank,
                $r['user_join']->user->name ?? '-',
                $r['user_join']->user->email ?? '-',
                $r['avg_tryout'] !== null ? round($r['avg_tryout'], 2) : '-',
            ];
        })->all();
        $fileName = 'laporan-monitor-'.str_replace(' ', '-', $package->title).'-'.now()->format('Y-m-d').'.xlsx';

        return Excel::download(new MonitorPackageExport($package->title, $rows), $fileName);
    }

    /**
     * Export laporan per peserta (ringkasan + riwayat tryout & kuis).
     */
    public function exportParticipant(UserJoin $userJoin)
    {
        if ($userJoin->status !== 'approved') {
            abort(404);
        }
        $userJoin->load(['user', 'package']);
        $user = $userJoin->user;
        $package = $userJoin->package;
        $tryoutRows = TransQuestion::with('exam')
            ->where('id_user', $user->id)
            ->where('id_package', $package->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($t) => [
                $t->exam?->title ?? 'Tryout',
                $t->total_score ?? 0,
                $t->status ?? '-',
                $t->created_at?->format('d/m/Y H:i') ?? '-',
            ])->all();
        $quizRows = TransQuiz::with('quiz.subject')
            ->where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($q) => [
                $q->quiz?->subject?->name ?? '-',
                $q->quiz?->title ?? 'Kuis',
                $q->total_score ?? 0,
                $q->created_at?->format('d/m/Y H:i') ?? '-',
            ])->all();

        $filename = 'laporan-monitor-'.str_replace(' ', '-', $user->name).'-'.now()->format('Y-m-d').'.xlsx';

        return Excel::download(
            new MonitorParticipantExport($user->name, $package->title, $tryoutRows, $quizRows),
            $filename
        );
    }

    /**
     * Detail satu attempt tryout: jawaban per soal, benar/salah, poin.
     */
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

        return view('admin.monitor-participants.tryout-detail', [
            'userJoin' => $userJoin,
            'user' => $userJoin->user,
            'package' => $userJoin->package,
            'trans' => $transQuestion,
            'exam' => $transQuestion->exam,
            'detailResults' => $detailResults,
        ]);
    }
}
