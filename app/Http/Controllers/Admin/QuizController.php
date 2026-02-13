<?php

namespace App\Http\Controllers\Admin;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\QuizRequest;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $data = Quiz::with('subject')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.quiz.index', compact('data', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = DB::table('subjects')->orderBy('name')->get();
        return view('admin.quiz.create', compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuizRequest $request)
    {
        $validated = $request->validated();
        Quiz::create($validated);
        return redirect()->route('quizzes.index')->with('success', 'Quiz berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Quiz::with('subject')->findOrFail($id);
        return view('admin.quiz.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Quiz::with('subject')->findOrFail($id);
        $subjects = DB::table('subjects')->orderBy('name')->get();
        return view('admin.quiz.edit', compact('data', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuizRequest $request, string $id)
    {
        $quiz = Quiz::findOrFail($id);
        $validated = $request->validated();
        $quiz->update($validated);
        return redirect()->route('quizzes.index')->with('success', 'Quiz berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('success', 'Quiz berhasil dihapus.');
    }
}
