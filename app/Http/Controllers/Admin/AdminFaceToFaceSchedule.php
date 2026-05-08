<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaceToFaceScheduleRequest;
use App\Http\Requests\Admin\UpdateFaceToFaceScheduleRequest;
use App\Models\FaceToFaceSchedule;
use App\Models\Package;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminFaceToFaceSchedule extends Controller
{
    public function index(): View
    {
        $schedules = FaceToFaceSchedule::query()
            ->with(['package:id,title', 'sessions.teacher:id,name'])
            ->withCount(['sessions', 'registrations'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.face-to-face-schedules.index', compact('schedules'));
    }

    public function create(): View
    {
        $packages = Package::where('status', 'active')->orderBy('title')->get(['id', 'title']);
        $teachers = Teacher::orderBy('name')->get(['id', 'name']);

        return view('admin.face-to-face-schedules.create', compact('packages', 'teachers'));
    }

    public function store(StoreFaceToFaceScheduleRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('sessions');
        $data['is_active'] = $request->boolean('is_active', true);

        $schedule = FaceToFaceSchedule::create($data);
        $schedule->sessions()->createMany($request->validated('sessions'));

        return redirect()
            ->route('admin.face-to-face-schedules.index')
            ->with('success', 'Jadwal tatap muka berhasil ditambahkan.');
    }

    public function edit(FaceToFaceSchedule $faceToFaceSchedule): View
    {
        $packages = Package::where('status', 'active')->orderBy('title')->get(['id', 'title']);
        $teachers = Teacher::orderBy('name')->get(['id', 'name']);
        $faceToFaceSchedule->load('sessions');

        return view('admin.face-to-face-schedules.edit', [
            'schedule'  => $faceToFaceSchedule,
            'packages'  => $packages,
            'teachers'  => $teachers,
        ]);
    }

    public function update(UpdateFaceToFaceScheduleRequest $request, FaceToFaceSchedule $faceToFaceSchedule): RedirectResponse
    {
        $data = $request->safe()->except('sessions');
        $data['is_active'] = $request->boolean('is_active');

        $faceToFaceSchedule->update($data);

        // Replace all sessions
        $faceToFaceSchedule->sessions()->delete();
        $faceToFaceSchedule->sessions()->createMany($request->validated('sessions'));

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

    public function participants(FaceToFaceSchedule $faceToFaceSchedule): View
    {
        $faceToFaceSchedule->load(['package:id,title', 'registrations.user:id,name,email']);

        return view('admin.face-to-face-schedules.participants', [
            'schedule' => $faceToFaceSchedule,
        ]);
    }
}
