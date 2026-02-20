@extends('layouts.app')

@section('title', 'Edit Data Buku')

@section('content')
    <div class="space-y-8 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Data Buku</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                    Edit data Buku.
                </p>
            </div>

            <x-button variant="secondary" href="{{ route('books.index') }}" class="rounded-xl">
                <i class="ti ti-arrow-left mr-2"></i> Kembali
            </x-button>
        </div>

        <x-card>
            <form action="{{ route('books.update', $book->id) }}" method="POST" class="space-y-6"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input label="NAMA BUKU" name="title" placeholder="Masukkan nama buku"
                        value="{{ old('title', $book->title) }}" required />
                    @error('title')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                    <x-textarea label="Penulis" name="author" placeholder="Masukan nama penulis contoh : J.K Rowling"
                        value="{{ old('author', $book->author) }}" rows="3"
                        required>{{ old('author', $book->author) }}</x-textarea>
                    @error('author')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
                <x-textarea label="Deksripsi buku" name="description" placeholder="Masukan deskripsi buku"
                    value="{{ old('description', $book->description) }}" rows="3"
                    required>{{ old('description', $book->description) }}</x-textarea>
                @error('description')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Cover Buku ( Image )<span class="text-rose-500">*</span>
                    </label>
                    @if ($book->cover_image)
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Cover Saat Ini:</p>
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover Lama"
                                class="w-40 h-56 object-cover rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                        </div>
                    @endif
                    <input type="file" name="cover_image" accept=".jpg,.jpeg,.png"
                        class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Format: JPG, JPEG, PNG. Maksimal 5MB.</p>
                    @error('cover_image')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <x-button type="submit" variant="primary" class="rounded-xl">
                        Update Buku
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
