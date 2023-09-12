<?php

namespace App\Application\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCaseNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'app_id' => [
                'required',
                'exists:appointments,id',
                'unique:case_notes,app_id',
            ],
            'title' => [
                'required',
                'string',
                'max:255'
            ],
            'description' => [
                'required',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'app_id.required' => 'Appointment ID is required',
            'app_id.exists' => 'Appointment ID does not exist',
            'app_id.unique' => 'Appointment ID already has a case note',
            'title.required' => 'Title is required',
            'title.string' => 'Title must be a string',
            'title.max' => 'Title must be less than 255 characters',
            'description.required' => 'Description is required',
            'description.text' => 'Description must be a text',
        ];
    }

}
