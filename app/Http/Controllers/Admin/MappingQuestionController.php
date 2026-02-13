<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\Quiz;
use App\Models\Subject;
use App\Models\BankQuestion;
use Illuminate\Http\Request;
use App\Models\MappingQuestion;
use App\Http\Controllers\Controller;

class MappingQuestionController extends Controller
{
    public function create(Request $request)
    {
        $type = $request->query('type', 'exam');
        $examId = $request->query('exam_id');
        $quizId = $request->query('quiz_id');
        $subjectId = $request->query('subject_id');

        $exams = Exam::with('package')->orderBy('created_at', 'desc')->get();
        $quizzes = Quiz::with('subject')->orderBy('created_at', 'desc')->get();

        $selectedExam = null;
        $selectedQuiz = null;
        $questions = collect();
        $mapped = collect();
        $subjects = Subject::orderBy('name')->get();

        if ($examId && $type === 'exam') {
            $selectedExam = Exam::findOrFail($examId);
            $query = BankQuestion::with('subject')
                ->whereNotIn('id', function ($sub) use ($selectedExam) {
                    $sub->select('id_question_bank')
                        ->from('mapping_questions')
                        ->where('id_exam', $selectedExam->id);
                });
            if ($subjectId) {
                $query->where('subject_id', $subjectId);
            }
            $questions = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
            $mapped = $selectedExam->mappingQuestions()
                ->with('questionBank.subject')
                ->latest()
                ->paginate(10, ['*'], 'mapped_page')
                ->withQueryString();
        }

        if ($quizId && $type === 'quiz') {
            $selectedQuiz = Quiz::findOrFail($quizId);
            $query = BankQuestion::with('subject')
                ->whereNotIn('id', function ($sub) use ($selectedQuiz) {
                    $sub->select('id_question_bank')
                        ->from('mapping_questions')
                        ->where('id_quiz', $selectedQuiz->id);
                });
            if ($subjectId) {
                $query->where('subject_id', $subjectId);
            }
            $questions = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
            $mapped = $selectedQuiz->mappingQuestions()
                ->with('questionBank.subject')
                ->latest()
                ->paginate(10, ['*'], 'mapped_page')
                ->withQueryString();
        }

        return view('admin.mapping-question.create', [
            'exams' => $exams,
            'quizzes' => $quizzes,
            'selectedExam' => $selectedExam,
            'selectedQuiz' => $selectedQuiz,
            'questions' => $questions,
            'mapped' => $mapped,
            'type' => $type,
            'examId' => $examId,
            'quizId' => $quizId,
            'subjectId' => $subjectId,
            'subjects' => $subjects,
        ]);
    }

    public function index(Request $request, Exam $exam)
    {
        $subjectId = $request->query('subject_id');

        $subjets = Subject::orderBy('name')->get();

        $query = BankQuestion::with('subject')
            ->whereNotIn('id', function ($sub) use ($exam) {
                $sub->select('id_question_bank')
                    ->from('mapping_questions')
                    ->where('id_exam', $exam->id);
            });
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        $questions = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $mapped = $exam->mappingQuestions()
            ->with('questionBank.subject')
            ->latest()
            ->paginate(10, ['*'], 'mapped_page')
            ->withQueryString();

        return view('admin.mapping-question.index', [
            'mappable' => $exam,
            'mappableType' => 'exam',
            'subjets' => $subjets,
            'questions' => $questions,
            'mapped' => $mapped,
            'subjectId' => $subjectId,
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
                'id_quiz' => null,
                'id_question_bank' => $questionId,
            ]);
        }

        return redirect()
            ->route('mapping-questions.manage', $exam)
            ->with('success', 'Soal berhasil ditambahkan ke ujian.');
    }

    public function random(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'total' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $subjectId = $validated['subject_id'] ?? null;
        $total = $validated['total'];

        $query = BankQuestion::query()
            ->whereNotIn('id', function ($sub) use ($exam) {
                $sub->select('id_question_bank')
                    ->from('mapping_questions')
                    ->where('id_exam', $exam->id);
            });

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        $ids = $query->inRandomOrder()->limit($total)->pluck('id');

        foreach ($ids as $id) {
            MappingQuestion::firstOrCreate([
                'id_exam' => $exam->id,
                'id_quiz' => null,
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

    public function indexForQuiz(Request $request, Quiz $quiz)
    {
        $subjectId = $request->query('subject_id');
        $subjects = Subject::orderBy('name')->get();

        $query = BankQuestion::with('subject')
            ->whereNotIn('id', function ($sub) use ($quiz) {
                $sub->select('id_question_bank')
                    ->from('mapping_questions')
                    ->where('id_quiz', $quiz->id);
            });
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        $questions = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $mapped = $quiz->mappingQuestions()
            ->with('questionBank.subject')
            ->latest()
            ->paginate(10, ['*'], 'mapped_page')
            ->withQueryString();

        return view('admin.mapping-question.index', [
            'mappable' => $quiz,
            'mappableType' => 'quiz',
            'subjets' => $subjects,
            'questions' => $questions,
            'mapped' => $mapped,
            'subjectId' => $subjectId,
        ]);
    }

    public function storeForQuiz(Request $request, Quiz $quiz): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'question_ids' => ['required', 'array'],
            'question_ids.*' => ['integer', 'exists:bank_questions,id'],
        ]);

        foreach ($validated['question_ids'] as $questionId) {
            MappingQuestion::firstOrCreate([
                'id_quiz' => $quiz->id,
                'id_exam' => null,
                'id_question_bank' => $questionId,
            ]);
        }

        return redirect()
            ->route('mapping-questions.quiz.manage', $quiz)
            ->with('success', 'Soal berhasil ditambahkan ke kuis.');
    }

    public function randomForQuiz(Request $request, Quiz $quiz): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'total' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $subjectId = $validated['subject_id'] ?? null;
        $total = $validated['total'];

        $query = BankQuestion::query()
            ->whereNotIn('id', function ($sub) use ($quiz) {
                $sub->select('id_question_bank')
                    ->from('mapping_questions')
                    ->where('id_quiz', $quiz->id);
            });
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        $ids = $query->inRandomOrder()->limit($total)->pluck('id');
        foreach ($ids as $id) {
            MappingQuestion::firstOrCreate([
                'id_quiz' => $quiz->id,
                'id_exam' => null,
                'id_question_bank' => $id,
            ]);
        }

        return redirect()
            ->route('mapping-questions.quiz.manage', $quiz)
            ->with('success', 'Soal acak berhasil ditambahkan ke kuis.');
    }

    public function showForQuiz(Quiz $quiz, MappingQuestion $mapping)
    {
        abort_unless($mapping->id_quiz === $quiz->id, 404);

        $mapping->load('questionBank.subject');

        return view('admin.mapping-question.show', [
            'mappable' => $quiz,
            'mappableType' => 'quiz',
            'mapping' => $mapping,
            'question' => $mapping->questionBank,
        ]);
    }

    public function destroyForQuiz(Quiz $quiz, MappingQuestion $mapping): \Illuminate\Http\RedirectResponse
    {
        abort_unless($mapping->id_quiz === $quiz->id, 404);

        $mapping->delete();

        return redirect()
            ->route('mapping-questions.quiz.manage', $quiz)
            ->with('success', 'Soal berhasil dihapus dari kuis.');
    }

    public function indexMappingQuestion(Request $request)
    {
        $search = $request->query('search');

        $examQuery = Exam::with(['package', 'mappingQuestions'])->whereHas('mappingQuestions');
        $quizQuery = Quiz::with(['subject', 'mappingQuestions'])->whereHas('mappingQuestions');

        if ($search) {
            $examQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('package', fn($sub) => $sub->where('title', 'like', '%' . $search . '%'));
            });
            $quizQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('subject', fn($sub) => $sub->where('name', 'like', '%' . $search . '%'));
            });
        }

        $examsWithMapping = $examQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'exam_page')->withQueryString();
        $quizzesWithMapping = $quizQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'quiz_page')->withQueryString();

        return view('admin.mapping-question.index-exam', [
            'examsWithMapping' => $examsWithMapping,
            'quizzesWithMapping' => $quizzesWithMapping,
            'search' => $search,
        ]);
    }
}
