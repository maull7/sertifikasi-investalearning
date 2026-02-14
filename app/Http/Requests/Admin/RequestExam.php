<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RequestExam extends FormRequest
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
        return [
            'package_id' => 'required|exists:packages,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'passing_grade' => 'required|integer|min:0|max:100',
            'total_questions' => 'nullable|integer|min:1',
            'type' => 'required|in:pretest,posttest',
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'package_id.required' => 'Paket wajib dipilih.',
            'package_id.exists' => 'Paket tidak valid.',
            'title.required' => 'Judul ujian wajib diisi.',
            'title.max' => 'Judul ujian maksimal 255 karakter.',
            'duration.required' => 'Durasi ujian wajib diisi.',
            'duration.integer' => 'Durasi harus berupa angka.',
            'duration.min' => 'Durasi minimal 1 menit.',
            'passing_grade.required' => 'Nilai kelulusan wajib diisi.',
            'passing_grade.integer' => 'Nilai kelulusan harus berupa angka.',
            'passing_grade.min' => 'Nilai kelulusan minimal 0.',
            'passing_grade.max' => 'Nilai kelulusan maksimal 100.',
            'total_questions.integer' => 'Jumlah soal harus berupa angka.',
            'total_questions.min' => 'Jumlah soal minimal 1.',
            'type.required' => 'Tipe ujian wajib dipilih.',
            'type.in' => 'Tipe ujian tidak valid. Pilih antara pretest atau posttest.',
        ];
    }
}
