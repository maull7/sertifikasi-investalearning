<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RequestBankQuestion extends FormRequest
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
        $rules = [
            'type_id' => 'required|exists:master_types,id',
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'answer' => 'required|in:a,b,c,d',
            'solution' => 'required|string',
            'explanation' => 'required|string',
        ];

        if ($this->isMethod('post')) {
            $rules['question_image'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        } else {
            $rules['question_image'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        }

        return $rules;
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'type_id.required' => 'Tipe soal wajib dipilih.',
            'type_id.exists' => 'Tipe soal tidak valid.',
            'question.required' => 'Soal wajib diisi.',
            'option_a.required' => 'Opsi A wajib diisi.',
            'option_b.required' => 'Opsi B wajib diisi.',
            'option_c.required' => 'Opsi C wajib diisi.',
            'option_d.required' => 'Opsi D wajib diisi.',
            'answer.required' => 'Jawaban wajib dipilih.',
            'answer.in' => 'Jawaban harus salah satu dari A, B, C, atau D.',
            'solution.required' => 'Pembahasan wajib diisi.',
            'explanation.required' => 'Penjelasan wajib diisi.',
            'question_image.image' => 'File harus berupa gambar.',
            'question_image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'question_image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}




