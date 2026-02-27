<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankQuestion;
use App\Models\Exam;
use App\Models\MappingQuestion;
use App\Models\Subject;
use Illuminate\Http\Request;

class MappingQuestionController extends Controller
{
    public function create(Request $request)
    {
        $examId = $request->query('exam_id');
        $subjectId = $request->query('subject_id');
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');

        $exams = Exam::with('package')
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedExam = null;
        $questions = collect();
        $mapped = collect();
        $toAddPerSubject = [];
        $subjectStatus = [];
        $subjects = Subject::orderBy('name')->get();

        $allowedSort = ['mapel', 'soal', 'jawaban', 'jenis', 'created_at'];
        if (! in_array($sortBy, $allowedSort)) {
            $sortBy = 'created_at';
        }
        if (! in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        if ($examId) {
            $selectedExam = Exam::findOrFail($examId)->load('subjects');
            $examSubjectIds = $selectedExam->subjects->pluck('id')->all();

            $query = BankQuestion::with('subject')
                ->whereNotIn('bank_questions.id', function ($sub) use ($selectedExam) {
                    $sub->select('id_question_bank')
                        ->from('mapping_questions')
                        ->where('id_exam', $selectedExam->id);
                });

            if (! empty($examSubjectIds)) {
                $query->whereIn('bank_questions.subject_id', $examSubjectIds);
            }
            if ($subjectId && in_array((int) $subjectId, array_map('intval', $examSubjectIds), true)) {
                $query->where('subject_id', $subjectId);
            }

            match ($sortBy) {
                'mapel' => $query->join('subjects', 'bank_questions.subject_id', '=', 'subjects.id')
                    ->orderBy('subjects.name', $sortOrder)
                    ->select('bank_questions.*'),
                'soal' => $query->orderBy('question', $sortOrder),
                'jawaban' => $query->orderBy('answer', $sortOrder),
                'jenis' => $query->orderBy('question_type', $sortOrder),
                default => $query->orderBy('bank_questions.created_at', $sortOrder),
            };

            $questions = $query->paginate(10)->withQueryString();
            $mapped = $selectedExam->mappingQuestions()
                ->with('questionBank.subject')
                ->latest()
                ->paginate(10, ['*'], 'mapped_page')
                ->withQueryString();

            $toAddPerSubject = $selectedExam->subjects->keyBy('id')->map(function ($s) use ($selectedExam) {
                $needed = (int) ($s->pivot->questions_count ?? 0);
                $already = $selectedExam->mappingQuestions()
                    ->whereHas('questionBank', fn ($q) => $q->where('subject_id', $s->id))
                    ->count();

                return max(0, $needed - $already);
            })->filter(fn ($n) => $n > 0)->all();
        }

        $subjectNeeds = $selectedExam
            ? $selectedExam->subjects->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'needed' => (int) ($s->pivot->questions_count ?? 0),
            ])->filter(fn ($s) => $s['needed'] > 0)->values()->all()
            : [];

        if ($selectedExam) {
            $subjectStatus = $selectedExam->subjects->map(function ($s) use ($selectedExam) {
                $needed = (int) ($s->pivot->questions_count ?? 0);
                $mapped = $selectedExam->mappingQuestions()
                    ->whereHas('questionBank', fn ($q) => $q->where('subject_id', $s->id))
                    ->count();

                return [
                    'id' => $s->id,
                    'name' => $s->name,
                    'needed' => $needed,
                    'mapped' => $mapped,
                    'is_full' => $needed > 0 && $mapped >= $needed,
                ];
            })->filter(fn ($s) => $s['needed'] > 0)->values()->all();
        }

        return view('admin.mapping-question.create', [
            'exams' => $exams,
            'selectedExam' => $selectedExam,
            'questions' => $questions,
            'mapped' => $mapped,
            'examId' => $examId,
            'subjectId' => $subjectId,
            'subjects' => $selectedExam ? $selectedExam->subjects : collect(),
            'subjectNeeds' => $subjectNeeds,
            'subjectStatus' => $subjectStatus ?? [],
            'toAddPerSubject' => $toAddPerSubject ?? [],
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    public function index(Request $request, Exam $exam)
    {
        $subjectId = $request->query('subject_id');
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');

        $exam->load('subjects');
        $examSubjectIds = $exam->subjects->pluck('id')->all();

        $allowedSort = ['mapel', 'soal', 'jawaban', 'jenis', 'created_at'];
        if (! in_array($sortBy, $allowedSort)) {
            $sortBy = 'created_at';
        }
        if (! in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query = BankQuestion::with('subject')
            ->whereNotIn('bank_questions.id', function ($sub) use ($exam) {
                $sub->select('id_question_bank')
                    ->from('mapping_questions')
                    ->where('id_exam', $exam->id);
            });

        if (! empty($examSubjectIds)) {
            $query->whereIn('bank_questions.subject_id', $examSubjectIds);
        }
        if ($subjectId && in_array((int) $subjectId, array_map('intval', $examSubjectIds), true)) {
            $query->where('subject_id', $subjectId);
        }

        match ($sortBy) {
            'mapel' => $query->join('subjects', 'bank_questions.subject_id', '=', 'subjects.id')
                ->orderBy('subjects.name', $sortOrder)
                ->select('bank_questions.*'),
            'soal' => $query->orderBy('question', $sortOrder),
            'jawaban' => $query->orderBy('answer', $sortOrder),
            'jenis' => $query->orderBy('question_type', $sortOrder),
            default => $query->orderBy('bank_questions.created_at', $sortOrder),
        };

        $questions = $query->paginate(10)->withQueryString();

        $mapped = $exam->mappingQuestions()
            ->with('questionBank.subject')
            ->latest()
            ->paginate(10, ['*'], 'mapped_page')
            ->withQueryString();

        $subjectNeeds = $exam->subjects->map(fn ($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'needed' => (int) ($s->pivot->questions_count ?? 0),
        ])->filter(fn ($s) => $s['needed'] > 0)->values()->all();

        $subjectStatus = $exam->subjects->map(function ($s) use ($exam) {
            $needed = (int) ($s->pivot->questions_count ?? 0);
            $mappedCount = $exam->mappingQuestions()
                ->whereHas('questionBank', fn ($q) => $q->where('subject_id', $s->id))
                ->count();

            return [
                'id' => $s->id,
                'name' => $s->name,
                'needed' => $needed,
                'mapped' => $mappedCount,
                'is_full' => $needed > 0 && $mappedCount >= $needed,
            ];
        })->filter(fn ($s) => $s['needed'] > 0)->values()->all();

        $toAddPerSubject = $exam->subjects->keyBy('id')->map(function ($s) use ($exam) {
            $needed = (int) ($s->pivot->questions_count ?? 0);
            $already = $exam->mappingQuestions()
                ->whereHas('questionBank', fn ($q) => $q->where('subject_id', $s->id))
                ->count();

            return max(0, $needed - $already);
        })->filter(fn ($n) => $n > 0)->all();

        return view('admin.mapping-question.index', [
            'mappable' => $exam,
            'mappableType' => 'exam',
            'subjets' => $exam->subjects,
            'questions' => $questions,
            'mapped' => $mapped,
            'subjectNeeds' => $subjectNeeds,
            'subjectStatus' => $subjectStatus,
            'toAddPerSubject' => $toAddPerSubject,
            'subjectId' => $subjectId,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    public function store(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'question_ids' => ['required', 'array'],
            'question_ids.*' => ['integer', 'exists:bank_questions,id'],
        ]);

        $questionIds = $validated['question_ids'];

        foreach ($questionIds as $questionId) {
            MappingQuestion::firstOrCreate([
                'id_exam' => $exam->id,
                'id_question_bank' => $questionId,
            ]);
        }

        return redirect()
            ->route('mapping-questions.manage', $exam)
            ->with('success', 'Soal berhasil ditambahkan ke ujian.');
    }

    public function random(Request $request, Exam $exam)
    {
        $exam->load('subjects');
        $subjectsWithCount = $exam->subjects->filter(fn ($s) => ((int) ($s->pivot->questions_count ?? 0)) > 0);

        if ($subjectsWithCount->isEmpty()) {
            return redirect()
                ->back()
                ->with('error', 'Ujian ini belum memiliki mata pelajaran dengan jumlah soal. Atur jumlah soal per mapel di Edit Ujian terlebih dahulu.');
        }

        $mappedBySubject = $exam->mappingQuestions()
            ->with('questionBank:id,subject_id')
            ->get()
            ->pluck('questionBank')
            ->filter()
            ->groupBy('subject_id')
            ->map(fn ($q) => $q->count());

        $added = 0;
        $subjectsWithoutQuestions = [];
        foreach ($subjectsWithCount as $subject) {
            $needed = (int) ($subject->pivot->questions_count ?? 0);
            if ($needed <= 0) {
                continue;
            }

            $alreadyMapped = (int) ($mappedBySubject->get($subject->id) ?? $mappedBySubject->get((string) $subject->id) ?? 0);
            $shortfall = max(0, $needed - $alreadyMapped);
            if ($shortfall === 0) {
                continue;
            }

            $ids = BankQuestion::query()
                ->where('subject_id', $subject->id)
                ->whereNotIn('id', function ($sub) use ($exam) {
                    $sub->select('id_question_bank')
                        ->from('mapping_questions')
                        ->where('id_exam', $exam->id);
                })
                ->inRandomOrder()
                ->limit($shortfall)
                ->pluck('id');

            if ($ids->isEmpty()) {
                $subjectsWithoutQuestions[] = $subject->name;
            }

            foreach ($ids as $id) {
                MappingQuestion::firstOrCreate([
                    'id_exam' => $exam->id,
                    'id_question_bank' => $id,
                ]);
                $added++;
            }
        }

        $referer = $request->headers->get('referer', '');
        $fromCreate = str_contains($referer, route('mapping-questions.create'));
        $redirect = redirect()
            ->to($fromCreate ? route('mapping-questions.create', ['exam_id' => $exam->id]) : route('mapping-questions.manage', $exam));

        if ($added > 0) {
            $redirect->with('success', "Soal acak berhasil ditambahkan ({$added} soal).");
        } else {
            $redirect->with('success', 'Semua mapel sudah memenuhi jumlah soal yang diperlukan.');
        }
        if (! empty($subjectsWithoutQuestions)) {
            $redirect->with('warning', 'Mapel berikut tidak memiliki soal tersedia di bank: '.implode(', ', $subjectsWithoutQuestions).'. Silakan tambah soal ke bank untuk mapel tersebut terlebih dahulu.');
        }

        return $redirect;
    }

    public function show(Exam $exam, MappingQuestion $mapping)
    {
        abort_unless($mapping->id_exam === $exam->id, 404);

        $mapping->load('questionBank.subject');

        return view('admin.mapping-question.show', [
            'mappable' => $exam,
            'mappableType' => 'exam',
            'mapping' => $mapping,
            'question' => $mapping->questionBank,
        ]);
    }

    public function destroy(Exam $exam, MappingQuestion $mapping)
    {
        abort_unless($mapping->id_exam === $exam->id, 404);

        $mapping->delete();

        return redirect()
            ->route('mapping-questions.manage', $exam)
            ->with('success', 'Soal berhasil dihapus dari ujian.');
    }

    public function indexMappingQuestion(Request $request)
    {
        $search = $request->query('search');

        $examQuery = Exam::with(['package', 'mappingQuestions'])->whereHas('mappingQuestions');

        if ($search) {
            $examQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhereHas('package', fn ($sub) => $sub->where('title', 'like', '%'.$search.'%'));
            });
        }

        $examsWithMapping = $examQuery->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.mapping-question.index-exam', [
            'examsWithMapping' => $examsWithMapping,
            'search' => $search,
        ]);
    }
}
