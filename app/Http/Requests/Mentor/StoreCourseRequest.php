<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'mentor';
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:50000'],
            'thumbnail' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            'modules' => ['required', 'array', 'min:1'],
            'modules.*.title' => ['required', 'string', 'max:255'],
            'modules.*.description' => ['nullable', 'string'],

            'modules.*.content_types' => ['nullable', 'array'],
            'modules.*.content_titles' => ['nullable', 'array'],
            'modules.*.content_titles.*' => ['nullable', 'string', 'max:255'],

            'modules.*.videos' => ['nullable', 'array'],
            'modules.*.videos.*' => ['nullable', 'file', 'mimes:mp4,avi,mov,mkv', 'max:51200'],

            'modules.*.pdfs' => ['nullable', 'array'],
            'modules.*.pdfs.*' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],

            'modules.*.exercise_questions' => ['nullable', 'array'],
            'modules.*.exercise_questions.*.text' => ['required_with:modules.*.exercise_questions', 'string'],
            'modules.*.exercise_questions.*.a' => ['required_with:modules.*.exercise_questions', 'string'],
            'modules.*.exercise_questions.*.b' => ['required_with:modules.*.exercise_questions', 'string'],
            'modules.*.exercise_questions.*.c' => ['required_with:modules.*.exercise_questions', 'string'],
            'modules.*.exercise_questions.*.d' => ['required_with:modules.*.exercise_questions', 'string'],
            'modules.*.exercise_questions.*.correct' => ['required_with:modules.*.exercise_questions', 'in:a,b,c,d'],
        ];
    }
}
