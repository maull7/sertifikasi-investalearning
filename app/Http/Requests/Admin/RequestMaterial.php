<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RequestMaterial extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => [
                'required_if:materi_type,File',
                'file',
                'mimes:pdf,doc,docx',
                'max:10240',
            ],
            'id_subject' => 'required|exists:subjects,id',
            'topic' => 'required|string|max:255',
            'materi_type' => 'required|in:File,Video',
            'url_link' => [
                'nullable',
                'required_if:materi_type,Video',
                'url',
            ],
        ];
    }
}
