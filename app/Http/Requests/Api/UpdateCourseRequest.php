<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $courseId = $this->route('course') ?? $this->input('id');
        
        return [
            'id' => ['required', 'integer', 'exists:courses,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', Rule::unique('courses', 'slug')->ignore($courseId)],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'instructor_id' => ['sometimes', 'integer', 'exists:users,id'],
            'level' => ['nullable', 'string', 'in:beginner,intermediate,advanced'],
            'language' => ['nullable', 'string', 'max:50'],
            'duration_in_minutes' => ['nullable', 'integer', 'min:0'],
            'what_you_will_learn' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
        ];
    }
}

