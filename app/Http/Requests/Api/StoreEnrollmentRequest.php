<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'payment_id' => ['nullable', 'string'],
            'order_id' => ['nullable', 'string'],
            'signature' => ['nullable', 'string'],
        ];

        // Only admin can set user_id (for enrolling other users)
        if (auth()->check() && auth()->user()->role === 'admin') {
            $rules['user_id'] = ['nullable', 'integer', 'exists:users,id'];
        }

        return $rules;
    }
}

