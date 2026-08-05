<?php

namespace App\Http\Requests\Summary;

use Illuminate\Foundation\Http\FormRequest;

class GenerateSummaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'note_id' => ['nullable', 'exists:notes,id'],
            'raw_content' => ['nullable', 'string'],
            'custom_instructions' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty($this->input('note_id')) && empty($this->input('raw_content'))) {
                $validator->errors()->add('content', 'You must either select a note or enter raw study content to summarize.');
            }
        });
    }
}
