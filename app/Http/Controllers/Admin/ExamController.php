<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RequestExam;
use App\Models\Exam;
use App\Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $data = Exam::with('package', 'subjects')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.exam.index', compact('data', 'search'));
    }

    public function create()
    {
        $packages = Package::all();

        return view('admin.exam.create', compact('packages'));
    }

    public function store(RequestExam $request)
    {
        $package = Package::findOrFail($request->validated('package_id'));
        $exam = Exam::create($request->safe()->except(['subject_id', 'subject_questions']));

        $subjects = $package->mappedSubjects()->get();
        $subjectQuestions = $request->validated('subject_questions') ?? [];
        $sync = [];
        foreach ($subjects as $subject) {
            $count = (int) ($subjectQuestions[$subject->id] ?? 0);
            $sync[$subject->id] = ['questions_count' => max(0, $count)];
        }
        $exam->subjects()->sync($sync);

        return redirect()->route('exams.index')->with('success', 'Ujian berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $data = Exam::with('package', 'subjects')->findOrFail($id);

        return view('admin.exam.show', compact('data'));
    }

    public function edit(string $id)
    {
        $data = Exam::with(['package', 'subjects'])->findOrFail($id);
        $packages = Package::all();

        $existingSubjectQuestions = old('subject_questions');
        if ($existingSubjectQuestions === null) {
            $existingSubjectQuestions = $data->subjects->keyBy('id')->map(
                fn($s) => (int) ($s->pivot->questions_count ?? 0)
            )->all();
        }

        return view('admin.exam.edit', compact('data', 'packages', 'existingSubjectQuestions'));
    }

    public function update(RequestExam $request, string $id)
    {
        $exam = Exam::findOrFail($id);
        $exam->update($request->safe()->except(['subject_id', 'subject_questions']));

        if ($request->has('package_id')) {
            $package = Package::findOrFail($request->validated('package_id'));
            $subjects = $package->mappedSubjects()->get();
            $subjectQuestions = $request->validated('subject_questions') ?? [];
            $currentPivot = $exam->subjects()->get()->keyBy('id');
            $sync = [];
            foreach ($subjects as $subject) {
                $count = (int) ($subjectQuestions[$subject->id] ?? $currentPivot->get($subject->id)?->pivot?->questions_count ?? 0);
                $sync[$subject->id] = ['questions_count' => max(0, $count)];
            }
            $exam->subjects()->sync($sync);
        }

        return redirect()->route('exams.index')->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return redirect()->route('exams.index')->with('success', 'Ujian berhasil dihapus.');
    }

    public function subjectsByPackage(Package $package): JsonResponse
    {
        $subjects = $package->mappedSubjects()
            ->get()
            ->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'code' => $s->code])
            ->values()
            ->all();

        return response()->json(['subjects' => $subjects]);
    }
}
