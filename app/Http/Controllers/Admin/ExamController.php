<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RequestExam;
use App\Models\Exam;
use App\Models\Package;
use App\Models\Subject;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $data = Exam::with('package')
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
        $subjects = Subject::all();
        return view('admin.exam.create', compact('packages', 'subjects'));
    }

    public function store(RequestExam $request)
    {
        Exam::create($request->validated());
        return redirect()->route('exams.index')->with('success', 'Ujian berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $data = Exam::with('package')->findOrFail($id);
        return view('admin.exam.show', compact('data'));
    }

    public function edit(string $id)
    {
        $data = Exam::with('package')->findOrFail($id);
        $packages = Package::all();
        $subjects = Subject::all();
        return view('admin.exam.edit', compact('data', 'packages', 'subjects'));
    }

    public function update(RequestExam $request, string $id)
    {
        $exam = Exam::findOrFail($id);
        $exam->update($request->validated());
        return redirect()->route('exams.index')->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();
        return redirect()->route('exams.index')->with('success', 'Ujian berhasil dihapus.');
    }
}
