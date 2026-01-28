<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_master_type' => ['required', 'integer', 'exists:master_types,id'],
            'id_package' => ['required', 'integer', 'exists:packages,id'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'teacher_ids' => ['required', 'array', 'min:1'],
            'teacher_ids.*' => ['integer', 'exists:teachers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_master_type.required' => 'Jenis wajib dipilih.',
            'id_package.required' => 'Paket wajib dipilih.',
            'user_ids.required' => 'Minimal pilih satu peserta.',
            'teacher_ids.required' => 'Minimal pilih satu pengajar.',
        ];
    }
}
