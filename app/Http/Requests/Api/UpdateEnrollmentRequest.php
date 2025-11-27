<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:enrollments,id'],
            'status' => ['sometimes', 'string', 'in:pending,completed,cancelled'],
            'progress_percentage' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'amount_paid' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}

