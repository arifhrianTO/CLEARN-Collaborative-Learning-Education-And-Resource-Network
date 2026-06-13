<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class SubmitExerciseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answer'   => ['required', 'array'],
            'answer.*' => ['nullable', 'integer', 'exists:options,id'],
        ];
    }
}
