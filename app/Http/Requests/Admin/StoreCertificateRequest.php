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
        $userIds = $this->input('user_ids', []);
        
        $rules = [
            'id_master_type' => ['required', 'integer', 'exists:master_types,id'],
            'id_package' => ['required', 'integer', 'exists:packages,id'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'certificate_numbers' => ['required', 'array'],
            'training_date_starts' => ['required', 'array'],
            'training_date_ends' => ['required', 'array'],
            'teacher_ids' => ['required', 'array', 'min:1'],
            'teacher_ids.*' => ['integer', 'exists:teachers,id'],
        ];

        // Validasi certificate_numbers dan training_dates hanya untuk user yang dipilih
        foreach ($userIds as $userId) {
            $rules["certificate_numbers.{$userId}"] = ['required', 'string', 'max:255'];
            $rules["training_date_starts.{$userId}"] = ['required', 'date'];
            $rules["training_date_ends.{$userId}"] = ['required', 'date', 'after_or_equal:training_date_starts.' . $userId];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'id_master_type.required' => 'Jenis wajib dipilih.',
            'id_package.required' => 'Paket wajib dipilih.',
            'user_ids.required' => 'Minimal pilih satu peserta.',
            'certificate_numbers.required' => 'Nomor sertifikat wajib diisi.',
            'training_date_starts.required' => 'Tanggal mulai pelatihan wajib diisi.',
            'training_date_ends.required' => 'Tanggal selesai pelatihan wajib diisi.',
            'training_date_ends.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'teacher_ids.required' => 'Minimal pilih satu pengajar.',
        ];
    }
}
