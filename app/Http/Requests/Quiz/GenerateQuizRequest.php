<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class GenerateQuizRequest extends FormRequest
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
            'difficulty' => ['required', 'string', 'in:easy,medium,hard'],
            'total_questions' => ['required', 'integer', 'min:3', 'max:20'],
            'timer_minutes' => ['required', 'integer', 'min:1', 'max:60'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (empty($this->input('summary_id')) && empty($this->input('note_id')) && empty($this->input('raw_content'))) {
                $validator->errors()->add('content', 'Please select a summary, a note, or enter study content to generate questions.');
            }
        });
    }
}
