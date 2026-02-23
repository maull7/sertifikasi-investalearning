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
        $exam = Exam::create($request->safe()->except('subject_id'));

        $subjectIds = $package->mappedSubjects()->get()->pluck('id');
        $exam->subjects()->sync($subjectIds);

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

        return view('admin.exam.edit', compact('data', 'packages'));
    }

    public function update(RequestExam $request, string $id)
    {
        $exam = Exam::findOrFail($id);
        $exam->update($request->safe()->except(['subject_id']));

        if ($request->has('package_id')) {
            $package = Package::findOrFail($request->validated('package_id'));
            $exam->subjects()->sync($package->mappedSubjects()->get()->pluck('id'));
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
            ->map(fn ($s) => ['id' => $s->id, 'name' => $s->name, 'code' => $s->code])
            ->values()
            ->all();

        return response()->json(['subjects' => $subjects]);
    }
}
