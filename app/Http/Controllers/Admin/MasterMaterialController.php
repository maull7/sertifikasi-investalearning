<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RequestMaterial;
use App\Models\Materials;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $data = Materials::with('package')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.master-material.index', compact('data', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $packages = Package::where('status', 'active')->get();
        return view('admin.master-material.create', compact('packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestMaterial $request)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materials', $fileName, 'public');

            $data['value'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_type'] = $file->getClientOriginalExtension();
            $data['file_size'] = $file->getSize();
        }

        Materials::create($data);
        return redirect()->route('master-materials.index')->with('success', 'Material berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Materials::with('package')->findOrFail($id);
        return view('admin.master-material.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Materials::findOrFail($id);
        $packages = Package::where('status', 'active')->get();
        return view('admin.master-material.edit', compact('data', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestMaterial $request, string $id)
    {
        $material = Materials::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('file')) {
            if ($material->value && Storage::disk('public')->exists($material->value)) {
                Storage::disk('public')->delete($material->value);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materials', $fileName, 'public');

            $data['value'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_type'] = $file->getClientOriginalExtension();
            $data['file_size'] = $file->getSize();
        } else {
            unset($data['file']);
        }

        $material->update($data);
        return redirect()->route('master-materials.index')->with('success', 'Material berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Materials::findOrFail($id);

        // Delete file if exists
        if ($data->value && Storage::disk('public')->exists($data->value)) {
            Storage::disk('public')->delete($data->value);
        }

        $data->delete();
        return redirect()->route('master-materials.index')->with('success', 'Material berhasil dihapus.');
    }

    /**
     * Serve file untuk preview
     */
    public function serveFile(string $id)
    {
        $material = Materials::findOrFail($id);

        if (!$material->value || !Storage::disk('public')->exists($material->value)) {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = Storage::disk('public')->path($material->value);
        $mimeType = Storage::disk('public')->mimeType($material->value);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $material->file_name . '"',
        ]);
    }

    /**
     * Download file
     */
    public function downloadFile(string $id)
    {
        $material = Materials::findOrFail($id);

        if (!$material->value || !Storage::disk('public')->exists($material->value)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($material->value, $material->file_name);
    }
}
