<?php

namespace App\Application\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCaseNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'sometimes',
                'string',
                'max:255'
            ],
            'description' => [
                'sometimes',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'Title must be a string',
            'title.max' => 'Title must be less than 255 characters',
            'description.text' => 'Description must be a text',
        ];
    }

}

