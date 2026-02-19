<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RequestBook;
use App\Models\Book;
use Illuminate\Http\Request;
use Svg\Tag\Rect;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestBook $request)
    {

        $validated = $request->validated();
        // Handle file upload if cover_image is provided
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('books', $filename, 'public');
            // Simpan path ke database
            $validated['cover_image'] = 'books/' . $filename;
        }
        // Create the book with validated data
        Book::create($validated);
        return redirect()->route('books.index')->with('success', 'Book created successfully.');
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
    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        return view('admin.books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestBook $request, string $id)
    {
        $validated = $request->validated();
        $book = Book::findOrFail($id);
        // Handle file upload if cover_image is provided
        if ($request->hasFile('cover_image')) {
            // Hapus file lama jika ada
            if ($book->cover_image && file_exists(public_path('storage/' . $book->cover_image))) {
                unlink(public_path('storage/' . $book->cover_image));
            }
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('books', $filename, 'public');
            // Simpan path ke database
            $validated['cover_image'] = 'books/' . $filename;
        }
        // Update the book with validated data
        $book->update($validated);
        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        if ($book->cover_image && file_exists(public_path('storage/' . $book->cover_image))) {
            unlink(public_path('storage/' . $book->cover_image));
        }
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
}
