<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaceToFaceScheduleRequest;
use App\Http\Requests\Admin\UpdateFaceToFaceScheduleRequest;
use App\Models\FaceToFaceSchedule;
use App\Models\Package;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


class AdminFaceToFaceSchedule extends Controller
{
    public function index(): View
    {
        $schedules = FaceToFaceSchedule::query()
            ->with(['package:id,title', 'teacher:id,name', 'subject:id,name'])
            ->orderByDesc('schedule_date')
            ->orderBy('start_time')
            ->paginate(20);

        return view('admin.face-to-face-schedules.index', compact('schedules'));
    }

    public function create(): View
    {
        $packages = Package::query()
            ->where('status', 'active')
            ->orderBy('title')
            ->get(['id', 'title']);

        $teachers = Teacher::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $subjects = Subject::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.face-to-face-schedules.create', compact('packages', 'teachers', 'subjects'));
    }

    public function store(StoreFaceToFaceScheduleRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active', true);

        FaceToFaceSchedule::query()->create($payload);

        return redirect()
            ->route('admin.face-to-face-schedules.index')
            ->with('success', 'Jadwal tatap muka berhasil ditambahkan.');
    }

    public function edit(FaceToFaceSchedule $faceToFaceSchedule): View
    {
        $packages = Package::query()
            ->where('status', 'active')
            ->orderBy('title')
            ->get(['id', 'title']);

        $teachers = Teacher::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $subjects = Subject::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.face-to-face-schedules.edit', [
            'schedule' => $faceToFaceSchedule,
            'packages' => $packages,
            'teachers' => $teachers,
            'subjects' => $subjects,
        ]);
    }

    public function update(UpdateFaceToFaceScheduleRequest $request, FaceToFaceSchedule $faceToFaceSchedule): RedirectResponse
    {
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active');

        $faceToFaceSchedule->update($payload);

        return redirect()
            ->route('admin.face-to-face-schedules.index')
            ->with('success', 'Jadwal tatap muka berhasil diperbarui.');
    }

    public function destroy(FaceToFaceSchedule $faceToFaceSchedule): RedirectResponse
    {
        $faceToFaceSchedule->delete();

        return redirect()
            ->route('admin.face-to-face-schedules.index')
            ->with('success', 'Jadwal tatap muka berhasil dihapus.');
    }
}
