<?php

namespace App\Http\Requests\Flashcard;

use Illuminate\Foundation\Http\FormRequest;

class GenerateFlashcardsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'summary_id' => ['nullable', 'exists:summaries,id'],
            'note_id' => ['nullable', 'exists:notes,id'],
            'raw_content' => ['nullable', 'string'],
            'count' => ['required', 'integer', 'min:5', 'max:25'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty($this->input('summary_id')) && empty($this->input('note_id')) && empty($this->input('raw_content'))) {
                $validator->errors()->add('content', 'Please select a summary, a note, or enter text to generate flashcards.');
            }
        });
    }
}
