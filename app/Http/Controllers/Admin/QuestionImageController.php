<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionImageController extends Controller
{
    public function index()
    {
        $images = QuestionImage::latest()->paginate(20);

        return view('admin.question-image.index', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'image.required' => 'Gambar wajib diupload.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $file = $request->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('question-images', $fileName, 'public');

        QuestionImage::create([
            'filename' => $file->getClientOriginalName(),
            'path' => $filePath,
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
        ]);

        return redirect()->route('question-images.index')->with('success', 'Gambar berhasil diupload.');
    }

    public function destroy(string $id)
    {
        $image = QuestionImage::findOrFail($id);

        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        return redirect()->route('question-images.index')->with('success', 'Gambar berhasil dihapus.');
    }
}
