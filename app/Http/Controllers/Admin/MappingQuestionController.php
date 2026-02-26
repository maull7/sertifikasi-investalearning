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
        }

        return view('admin.mapping-question.create', [
            'exams' => $exams,
            'selectedExam' => $selectedExam,
            'questions' => $questions,
            'mapped' => $mapped,
            'examId' => $examId,
            'subjectId' => $subjectId,
            'subjects' => $selectedExam ? $selectedExam->subjects : collect(),
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

        return view('admin.mapping-question.index', [
            'mappable' => $exam,
            'mappableType' => 'exam',
            'subjets' => $exam->subjects,
            'questions' => $questions,
            'mapped' => $mapped,
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
        $examSubjectIds = $exam->subjects->pluck('id')->all();

        if (empty($examSubjectIds)) {
            return redirect()
                ->route('mapping-questions.manage', $exam)
                ->with('error', 'Ujian ini belum memiliki mata pelajaran. Atur jumlah soal per mapel di Edit Ujian terlebih dahulu.');
        }

        $rules = [
            'total' => ['required', 'integer', 'min:1', 'max:1000'],
        ];
        if (! empty($examSubjectIds)) {
            $rules['subject_id'] = ['nullable', 'integer', 'in:'.implode(',', $examSubjectIds)];
        }

        $validated = $request->validate($rules);
        $subjectId = isset($validated['subject_id']) ? (int) $validated['subject_id'] : null;
        $total = $validated['total'];

        $query = BankQuestion::query()
            ->whereNotIn('id', function ($sub) use ($exam) {
                $sub->select('id_question_bank')
                    ->from('mapping_questions')
                    ->where('id_exam', $exam->id);
            });

        if (! empty($examSubjectIds)) {
            $query->whereIn('subject_id', $examSubjectIds);
        }
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        $ids = $query->inRandomOrder()->limit($total)->pluck('id');

        foreach ($ids as $id) {
            MappingQuestion::firstOrCreate([
                'id_exam' => $exam->id,
                'id_question_bank' => $id,
            ]);
        }

        return redirect()
            ->route('mapping-questions.manage', $exam)
            ->with('success', 'Soal acak berhasil ditambahkan.');
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
