<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaceToFaceScheduleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'package_id'        => ['required', 'integer', 'exists:packages,id'],
            'title'             => ['required', 'string', 'max:255'],
            'room_name'         => ['required', 'string', 'max:120'],
            'zoom_join_url'     => ['nullable', 'url', 'max:500'],
            'zoom_meeting_id'   => ['nullable', 'string', 'max:60'],
            'zoom_passcode'     => ['nullable', 'string', 'max:120'],
            'is_active'         => ['nullable', 'boolean'],
            'sessions'          => ['required', 'array', 'min:1'],
            'sessions.*.session_date' => ['required', 'date'],
            'sessions.*.start_time'   => ['required', 'date_format:H:i'],
            'sessions.*.end_time'     => ['required', 'date_format:H:i', 'after:sessions.*.start_time'],
            'sessions.*.teacher_id'   => ['nullable', 'integer', 'exists:teachers,id'],
            'sessions.*.name'         => ['required', 'string', 'max:255'],
        ];
    }
}
