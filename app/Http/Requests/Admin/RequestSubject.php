<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RequestSubject extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $subject = $this->route('subject');
        $subjectId = $subject ? $subject->id : null;

        return [
            'master_type_id' => 'required|exists:master_types,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:subjects,code,' . ($subjectId ?? 'null'),
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'master_type_id.required' => 'Jenis wajib dipilih.',
            'master_type_id.exists' => 'Jenis tidak valid.',
            'name.required' => 'Nama mata pelajaran wajib diisi.',
            'name.max' => 'Nama mata pelajaran maksimal 255 karakter.',
            'code.max' => 'Kode mata pelajaran maksimal 50 karakter.',
            'code.unique' => 'Kode mata pelajaran sudah digunakan.',
        ];
    }
}
