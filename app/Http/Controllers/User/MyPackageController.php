<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Package;
use App\Models\StatusMateri;
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
            ->with(['package.masterType.subjects.materials'])
            ->whereHas('package', fn($q) => $q->where('status', 'active'))
            ->paginate(12);

        return view('user.my-packages.index', compact('joinedPackages'));
    }

    public function show(Package $package): View
    {
        $user = Auth::user();

        $userJoin = UserJoin::where('user_id', $user->id)
            ->where('id_package', $package->id)
            ->first();

        if (!$userJoin) {
            abort(403, 'Anda belum bergabung dengan package ini.');
        }

        $package->load(['masterType.subjects' => fn($q) => $q->with(['materials' => fn($mq) => $mq->with('subject'), 'quizzes' => fn($qq) => $qq->with('subject')])]);
        $subjects = $package->masterType ? $package->masterType->subjects : collect();

        $materialIdsBySubject = $subjects->keyBy('id')->map(fn($s) => $s->materials->pluck('id')->filter()->values());
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

        return view('user.my-packages.show', compact('package', 'subjects', 'subjectProgress'));
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
