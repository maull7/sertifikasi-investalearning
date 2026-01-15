<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Exams;
use App\Models\MappingQuestions;
use App\Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function show(Package $package, Exams $exam): View
    {
        $user = Auth::user();
        
        $userJoin = \App\Models\UserJoins::where('user_id', $user->id)
            ->where('id_package', $package->id)
            ->first();

        if (!$userJoin) {
            abort(403, 'Anda belum bergabung dengan package ini.');
        }

        if ($exam->package_id !== $package->id) {
            abort(404, 'Ujian tidak ditemukan dalam package ini.');
        }

        $totalQuestions = MappingQuestions::where('id_exam', $exam->id)->count();

        return view('user.exams.show', compact('package', 'exam', 'totalQuestions'));
    }

    public function getQuestions(Request $request, Package $package, Exams $exam): JsonResponse
    {
        $user = Auth::user();
        
        $userJoin = \App\Models\UserJoins::where('user_id', $user->id)
            ->where('id_package', $package->id)
            ->first();

        if (!$userJoin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($exam->package_id !== $package->id) {
            return response()->json(['error' => 'Exam not found'], 404);
        }

        $page = $request->get('page', 1);
        $perPage = 1; // Satu soal per halaman

        $mappingQuestions = MappingQuestions::where('id_exam', $exam->id)
            ->with('questionBank.type')
            ->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);

        $questions = $mappingQuestions->map(function ($mapping) {
            $question = $mapping->questionBank;
            return [
                'id' => $question->id,
                'mapping_id' => $mapping->id,
                'question_type' => $question->question_type,
                'question' => $question->question,
                'question_image_url' => $question->question_type === 'Image' 
                    ? (str_starts_with($question->question, 'http') 
                        ? $question->question 
                        : asset('storage/' . ltrim($question->question, '/')))
                    : null,
                'option_a' => $question->option_a,
                'option_b' => $question->option_b,
                'option_c' => $question->option_c,
                'option_d' => $question->option_d,
                'type' => $question->type ? $question->type->name_type : null,
            ];
        });

        return response()->json([
            'questions' => $questions,
            'current_page' => $mappingQuestions->currentPage(),
            'last_page' => $mappingQuestions->lastPage(),
            'total' => $mappingQuestions->total(),
            'has_more' => $mappingQuestions->hasMorePages(),
        ]);
    }
}

