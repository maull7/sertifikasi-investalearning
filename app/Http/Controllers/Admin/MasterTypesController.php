<?php

namespace App\Http\Controllers\Admin;

use App\Models\MasterType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MasterTypesTemplateExport;
use App\Http\Requests\Admin\RequestMasterType;
use PhpParser\Lexer\TokenEmulator\VoidCastEmulator;

class MasterTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $data = MasterType::when($search, function ($query, $search) {
            $query->where('name_type', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString(); // biar search kebawa pas ganti page

        return view('admin.master-type.index', compact('data', 'search'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestMasterType $request)
    {
        MasterType::create($request->validated());
        return redirect()->route('master-types.index')
            ->with('success', 'Jenis berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = MasterType::findOrFail($id);
        return view('admin.master-types.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = MasterType::findOrFail($id);
        return view('admin.master-type.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestMasterType $request, string $id)
    {
        $data = MasterType::findOrFail($id);
        $data->update($request->validated());
        return redirect()->route('master-types.index')->with('success', 'Jenis berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = MasterType::findOrFail($id);
        $data->delete();
        return redirect()->route('master-types.index')->with('success', 'Jenis berhasil dihapus.');
    }

    public function ExportTemplate()
    {
        return Excel::download(new MasterTypesTemplateExport, 'jenis_template.xlsx');
    }
    public function ImportTemplate(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new \App\Imports\MasterTypesImport, $request->file('file'));
            return redirect()->route('master-types.index')->with('success', 'Data jenis berhasil diimpor.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()->back()->withErrors($errorMessages);
        }
    }
}
