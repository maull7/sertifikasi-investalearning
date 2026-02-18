<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use App\Models\Subject;
use App\Models\Material;
use App\Models\StatusMateri;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\RequestMaterial;

class MasterMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $data = Material::with('subject')
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
        $subjects = Subject::all();
        return view('admin.master-material.create', compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestMaterial $request)
    {

        $data = $request->validated();

        if ($request->materi_type === 'File') {
            if (!$request->hasFile('file')) {
                return redirect()->back()->withErrors(['file' => 'File harus diunggah untuk tipe materi File'])->withInput();
            }
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('materials', $fileName, 'public');

                $data['value'] = $filePath;
                $data['file_name'] = $file->getClientOriginalName();
                $data['file_type'] = $file->getClientOriginalExtension();
                $data['file_size'] = $file->getSize();
            }
        } else {
            // Untuk tipe Video, simpan URL link ke kolom 'value'
            $data['value'] = $request->input('url_link');
            // Kosongkan kolom file terkait
            $data['file_name'] = null;
            $data['file_type'] = null;
            $data['file_size'] = null;
        }

        Material::create($data);
        return redirect()->route('master-materials.index')->with('success', 'Material berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Material::with('package')->findOrFail($id);
        return view('admin.master-material.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Material::findOrFail($id);
        $subjects = Subject::all();
        return view('admin.master-material.edit', compact('data', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestMaterial $request, string $id)
    {
        $material = Material::findOrFail($id);
        $data = $request->validated();

        if ($request->materi_type === 'Video') {
            // Untuk tipe Video, simpan URL link ke kolom 'value'
            $data['value'] = $request->input('url_link');
            // Kosongkan kolom file terkait
            $data['file_name'] = null;
            $data['file_type'] = null;
            $data['file_size'] = null;
        } else {
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
        }
        $material->update($data);
        return redirect()->route('master-materials.index')->with('success', 'Material berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Material::findOrFail($id);

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
        $material = Material::findOrFail($id);

        if (!$material->value || !Storage::disk('public')->exists($material->value)) {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = Storage::disk('public')->path($material->value);
        $mimeType = Storage::disk('public')->mimeType($material->value);
        $statusMateri = StatusMateri::where('id_user', Auth::user()->id)
            ->where('id_material', $material->id)
            ->first();
        if (!$statusMateri || $statusMateri->status !== 'completed') {
            StatusMateri::create([
                'id_user' => Auth::user()->id,
                'id_material' => $material->id,
                'id_subject' => $material->id_subject,
                'status' => 'in_progress',
            ]);
        }
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
        $material = Material::findOrFail($id);

        if (!$material->value || !Storage::disk('public')->exists($material->value)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($material->value, $material->file_name);
    }
}
