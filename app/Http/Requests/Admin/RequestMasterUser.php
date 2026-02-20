<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestMasterUser extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        $emailRule = ['required', 'string', 'email', 'max:255'];
        if ($user) {
            $emailRule[] = Rule::unique('users', 'email')->ignore($user->id);
        } else {
            $emailRule[] = Rule::unique('users', 'email');
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => $emailRule,
            'password' => [$this->isMethod('PUT') || $this->isMethod('PATCH') ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['Admin', 'Petugas'])],
            'phone' => ['required', 'numeric']
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role harus Admin atau Petugas.',
            'phone.required' => 'nomor telepon wajib di isi'
        ];
    }
}
