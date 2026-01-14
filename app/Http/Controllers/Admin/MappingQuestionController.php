<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankQuestions;
use App\Models\Exams;
use App\Models\MasterTypes;
use App\Models\MappingQuestions;
use Illuminate\Http\Request;

class MappingQuestionController extends Controller
{
    public function create(Request $request)
    {
        $examId = $request->query('exam_id');
        $typeId = $request->query('type_id');

        $exams = Exams::with('package')
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedExam = null;
        $types = collect();
        $questions = collect();
        $mapped = collect();

        if ($examId) {
            $selectedExam = Exams::findOrFail($examId);

            $types = MasterTypes::orderBy('name_type')->get();

            $query = BankQuestions::with('type')
                ->whereNotIn('id', function ($sub) use ($selectedExam) {
                    $sub->select('id_question_bank')
                        ->from('mapping_questions')
                        ->where('id_exam', $selectedExam->id);
                });

            if ($typeId) {
                $query->where('type_id', $typeId);
            }

            $questions = $query
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->withQueryString();

            $mapped = $selectedExam->mappingQuestions()
                ->with('questionBank.type')
                ->latest()
                ->paginate(10, ['*'], 'mapped_page')
                ->withQueryString();
        }

        return view('admin.mapping-question.create', [
            'exams' => $exams,
            'selectedExam' => $selectedExam,
            'types' => $types,
            'questions' => $questions,
            'mapped' => $mapped,
            'examId' => $examId,
            'typeId' => $typeId,
        ]);
    }

    public function index(Request $request, Exams $exam)
    {
        $typeId = $request->query('type_id');

        $types = MasterTypes::orderBy('name_type')->get();

        $query = BankQuestions::with('type')
            ->whereNotIn('id', function ($sub) use ($exam) {
                $sub->select('id_question_bank')
                    ->from('mapping_questions')
                    ->where('id_exam', $exam->id);
            });

        if ($typeId) {
            $query->where('type_id', $typeId);
        }

        $questions = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $mapped = $exam->mappingQuestions()
            ->with('questionBank.type')
            ->latest()
            ->paginate(10, ['*'], 'mapped_page')
            ->withQueryString();

        return view('admin.mapping-question.index', [
            'exam' => $exam,
            'types' => $types,
            'questions' => $questions,
            'mapped' => $mapped,
            'typeId' => $typeId,
        ]);
    }

    public function store(Request $request, Exams $exam)
    {
        $validated = $request->validate([
            'question_ids' => ['required', 'array'],
            'question_ids.*' => ['integer', 'exists:bank_questions,id'],
        ]);

        $questionIds = $validated['question_ids'];

        foreach ($questionIds as $questionId) {
            MappingQuestions::firstOrCreate([
                'id_exam' => $exam->id,
                'id_question_bank' => $questionId,
            ]);
        }

        return redirect()
            ->route('mapping-questions.index', $exam)
            ->with('success', 'Soal berhasil ditambahkan ke ujian.');
    }

    public function random(Request $request, Exams $exam)
    {
        $validated = $request->validate([
            'type_id' => ['nullable', 'exists:master_types,id'],
            'total' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $typeId = $validated['type_id'] ?? null;
        $total = $validated['total'];

        $query = BankQuestions::query()
            ->whereNotIn('id', function ($sub) use ($exam) {
                $sub->select('id_question_bank')
                    ->from('mapping_questions')
                    ->where('id_exam', $exam->id);
            });

        if ($typeId) {
            $query->where('type_id', $typeId);
        }

        $ids = $query->inRandomOrder()->limit($total)->pluck('id');

        foreach ($ids as $id) {
            MappingQuestions::firstOrCreate([
                'id_exam' => $exam->id,
                'id_question_bank' => $id,
            ]);
        }

        return redirect()
            ->route('mapping-questions.index', $exam)
            ->with('success', 'Soal acak berhasil ditambahkan.');
    }

    public function show(Exams $exam, MappingQuestions $mapping)
    {
        abort_unless($mapping->id_exam === $exam->id, 404);

        $mapping->load('questionBank.type');

        return view('admin.mapping-question.show', [
            'exam' => $exam,
            'mapping' => $mapping,
            'question' => $mapping->questionBank,
        ]);
    }

    public function destroy(Exams $exam, MappingQuestions $mapping)
    {
        abort_unless($mapping->id_exam === $exam->id, 404);

        $mapping->delete();

        return redirect()
            ->route('mapping-questions.index', $exam)
            ->with('success', 'Soal berhasil dihapus dari ujian.');
    }
    public function indexMappingQuestion(Request $request)
    {
        $search = $request->query('search');

        $query = Exams::with(['package', 'mappingQuestions'])
            ->whereHas('mappingQuestions');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('package', function ($sub) use ($search) {
                        $sub->where('title', 'like', '%' . $search . '%');
                    });
            });
        }

        $data = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.mapping-question.index-exam', [
            'data' => $data,
            'search' => $search,
        ]);
    }
}


