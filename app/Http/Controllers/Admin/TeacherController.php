<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RequestTeacher;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $teachers = Teacher::query()->orderBy('name')->paginate(10);

        return view('admin.teacher.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.teacher.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestTeacher $request): RedirectResponse
    {
        $validated = $request->validated();

        Teacher::query()->create($validated);

        return redirect()
            ->route('teacher.index')
            ->with('success', 'Berhasil menambahkan pengajar.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher): View
    {
        return view('admin.teacher.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestTeacher $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validated();

        $teacher->update($validated);

        return redirect()
            ->route('teacher.index')
            ->with('success', 'Berhasil memperbarui data pengajar.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher): RedirectResponse
    {
        $teacher->delete();

        return redirect()
            ->route('teacher.index')
            ->with('success', 'Berhasil menghapus pengajar.');
    }
}
