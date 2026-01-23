<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use App\Models\MasterType;
use Illuminate\Http\Request;
use App\Exports\MasterSubjectExport;
use App\Http\Controllers\Controller;
use App\Imports\MasterSubjectImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Admin\RequestSubject;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $typeId = $request->query('type_id');

        $types = MasterType::orderBy('name_type')->get();

        $data = Subject::with('type')
            ->when($typeId, function ($query, $typeId) {
                $query->where('master_type_id', $typeId);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('code', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.subject.index', [
            'data' => $data,
            'search' => $search,
            'types' => $types,
            'typeId' => $typeId,
        ]);
    }

    public function create()
    {
        $types = MasterType::orderBy('name_type')->get();

        return view('admin.subject.create', [
            'types' => $types,
        ]);
    }

    public function store(RequestSubject $request)
    {
        Subject::create($request->validated());

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Subject $subject)
    {
        $types = MasterType::orderBy('name_type')->get();

        return view('admin.subject.edit', [
            'data' => $subject,
            'types' => $types,
        ]);
    }

    public function update(RequestSubject $request, Subject $subject)
    {
        $subject->update($request->validated());

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
    public function TemplateExport()
    {
        return Excel::download(new MasterSubjectExport, 'template_master_mapel.xlsx');
    }
    public function ImportExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');

        Excel::import(new MasterSubjectImport, $file);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Data master mata pelajaran berhasil diimport.');
    }
}
