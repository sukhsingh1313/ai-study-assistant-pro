<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg,webp', 'max:10240'], // 10MB max
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'Only PDF documents and image files (PNG, JPG, WEBP) are allowed.',
            'file.max' => 'The uploaded file must not exceed 10MB in size.',
        ];
    }
}
