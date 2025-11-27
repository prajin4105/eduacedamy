<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:courses,slug'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'instructor_id' => ['required', 'integer', 'exists:users,id'],
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

