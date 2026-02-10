<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RequestBankQuestion extends FormRequest
{
    protected function prepareForValidation(): void
    {
        // Ensure question is always a string for DB compatibility (question column is not nullable)
        if (($this->input('question_type') ?? 'Text') === 'Image') {
            $this->merge(['question' => $this->input('question', '')]);
        }
    }

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
        $rules = [
            'subject_id' => 'required|exists:subjects,id',
            'question_type' => 'required|in:Text,Image',
            'question' => 'nullable|string|required_if:question_type,Text',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'option_e' => 'nullable|string',
            'answer' => 'required|in:a,b,c,d,e',
            'solution' => 'required|string',
            'explanation' => 'required|string',
        ];

        $rules['question_file'] = [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:2048',
            'required_if:question_type,Image',
        ];

        return $rules;
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'subject_id.exists' => 'Mata pelajaran tidak valid.',
            'question_type.required' => 'Jenis soal wajib dipilih.',
            'question_type.in' => 'Jenis soal harus Text atau Image.',
            'question.required_if' => 'Soal wajib diisi jika jenis soal Text.',
            'option_a.required' => 'Opsi A wajib diisi.',
            'option_b.required' => 'Opsi B wajib diisi.',
            'option_c.required' => 'Opsi C wajib diisi.',
            'option_d.required' => 'Opsi D wajib diisi.',
            'option_e.nullable' => 'Opsi E tidak wajib diisi.',
            'answer.required' => 'Jawaban wajib dipilih.',
            'answer.in' => 'Jawaban harus salah satu dari A, B, C, D, atau E.',
            'solution.required' => 'Pembahasan wajib diisi.',
            'explanation.required' => 'Penjelasan wajib diisi.',
            'question_file.image' => 'File harus berupa gambar.',
            'question_file.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'question_file.max' => 'Ukuran gambar maksimal 2MB.',
            'question_file.required_if' => 'Gambar soal wajib diupload jika jenis soal Image.',
        ];
    }
}
