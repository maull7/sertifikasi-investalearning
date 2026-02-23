<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Package;
use App\Models\StatusMateri;
use App\Models\TransQuestion;
use App\Models\UserJoin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyPackageController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $joinedPackages = UserJoin::where('user_id', $user->id)
            ->with(['package.mappedSubjects.materials', 'package.masterType'])
            ->whereHas('package', fn ($q) => $q->where('status', 'active'))
            ->paginate(12);

        return view('user.my-packages.index', compact('joinedPackages'));
    }

    public function show(Package $package): View
    {
        $user = Auth::user();

        $userJoin = UserJoin::where('user_id', $user->id)
            ->where('id_package', $package->id)
            ->first();

        if (! $userJoin) {
            abort(403, 'Anda belum bergabung dengan package ini.');
        }

        $subjects = $package->getSubjectsForPackage();
        $subjects->load(['materials' => fn ($q) => $q->with('subject'), 'quizzes' => fn ($q) => $q->with('subject')]);

        $materialIdsBySubject = $subjects->keyBy('id')->map(fn ($s) => $s->materials->pluck('id')->filter()->values());
        $completedMaterialIds = StatusMateri::where('id_user', $user->id)
            ->where('status', 'completed')
            ->pluck('id_material');

        $subjectProgress = [];
        foreach ($subjects as $subject) {
            $materialIds = $materialIdsBySubject->get($subject->id, collect());
            $total = $materialIds->count();
            $read = $materialIds->intersect($completedMaterialIds)->count();
            $subjectProgress[$subject->id] = [
                'total' => $total,
                'read' => $read,
                'can_do_quiz' => $total > 0 ? $read >= $total : true,
            ];
        }

        $exams = \App\Models\Exam::with('subjects')->where('package_id', $package->id)->get();
        $examAttemptsByExam = TransQuestion::where('id_user', $user->id)
            ->whereIn('id_exam', $exams->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('id_exam');

        return view('user.my-packages.show', compact('package', 'subjects', 'subjectProgress', 'examAttemptsByExam'));
    }

    public function markAsRead(Material $material): RedirectResponse
    {
        $user = Auth::user();

        $statusMateri = StatusMateri::where('id_user', $user->id)
            ->where('id_material', $material->id)
            ->first();

        if ($statusMateri) {
            $statusMateri->update(['status' => 'completed']);
        } else {
            StatusMateri::create([
                'id_user' => $user->id,
                'id_material' => $material->id,
                'id_subject' => $material->id_subject,
                'status' => 'completed',
            ]);
        }

        return redirect()->back()->with('success', 'Materi telah ditandai sebagai dibaca.');
    }
}
