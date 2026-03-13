<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->role === 'Admin';
    }

    public function rules(): array
    {
        return [
            'front_background' => ['nullable', 'image', 'max:2048'],
            'left_signature_image' => ['nullable', 'image', 'max:1024'],
            'right_signature_image' => ['nullable', 'image', 'max:1024'],
            'left_signature_name' => ['nullable', 'string', 'max:255'],
            'left_signature_title' => ['nullable', 'string', 'max:255'],
            'right_signature_name' => ['nullable', 'string', 'max:255'],
            'right_signature_title' => ['nullable', 'string', 'max:255'],
            'schema_title' => ['nullable', 'string', 'max:255'],
            'schema_description' => ['nullable', 'string'],
            'uk_list' => ['nullable', 'string'],
            'facilitator_list' => ['nullable', 'string'],
        ];
    }
}

