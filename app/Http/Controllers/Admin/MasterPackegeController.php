<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use App\Models\MasterType;
use Illuminate\Http\Request;
use App\Exports\PaketTemplate;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Admin\RequestPackage;
use App\Imports\PaketImport;

class MasterPackegeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $data = Package::when($search, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString(); // biar search kebawa pas ganti page

        return view('admin.master-package.index', compact('data', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = MasterType::all();
        return view('admin.master-package.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestPackage $request)
    {
        Package::create($request->validated());
        return redirect()->route('master-packages.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Package::with('users')->findOrFail($id);
        return view('admin.master-package.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $types = MasterType::all();
        $data = Package::findOrFail($id);
        return view('admin.master-package.edit', compact('data', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestPackage $request, string $id)
    {
        $data = Package::findOrFail($id);
        $data->update($request->validated());
        return redirect()->route('master-packages.index')->with('success', 'Paket berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Package::findOrFail($id);
        $data->delete();
        return redirect()->route('master-packages.index')->with('success', 'Paket berhasil dihapus.');
    }

    public function DownloadTemplate()
    {
        return Excel::download(new PaketTemplate, 'template_paket.xlsx');
    }

    public function importPackage(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new PaketImport, $request->file('file'));
            return redirect()->route('master-packages.index')->with('success', 'Data paket berhasil diimpor.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }

            return redirect()->back()->withErrors($errorMessages);
        }
    }
    public function toggleActive(Package $package)
    {
        $package->status = $package->status === 'active' ? 'inactive' : 'active';
        $package->save();
        $msg = $package->status === 'active' ? 'aktifkan' : 'nonaktifkan';
        return redirect()->route('master-packages.index')->with('success', "Paket berhasil {$msg}.");
    }
}
