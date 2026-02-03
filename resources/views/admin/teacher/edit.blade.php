@extends('layouts.app')

@section('title', 'Edit Pengajar')

@section('content')
    <div class="space-y-8 pb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Edit Pengajar</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                    Perbarui data pengajar.
                </p>
            </div>

            <x-button variant="secondary" href="{{ route('teacher.index') }}" class="rounded-xl">
                <i class="ti ti-arrow-left mr-2"></i> Kembali
            </x-button>
        </div>

        <x-card>
            <form action="{{ route('teacher.update', $teacher) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <x-input 
                        label="Nama Pengajar" 
                        name="name" 
                        placeholder="Masukkan nama pengajar" 
                        value="{{ old('name',$teacher->name) }}"
                        required 
                    />

                     <x-input 
                        label="NIP Pengajar" 
                        name="nip" 
                        placeholder="Masukkan nip pengajar" 
                        value="{{ old('nip', $teacher->nip) }}"
                        required 
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <x-input 
                        label="Email Pengajar" 
                        type="email"
                        name="email" 
                        placeholder="Email nama pengajar" 
                        value="{{ old('email', $teacher->email) }}"
                        required 
                    />
                   
                </div>

                <div class="flex justify-end">
                    <x-button type="submit" variant="primary" class="rounded-xl">
                        Update Pengajar
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection



