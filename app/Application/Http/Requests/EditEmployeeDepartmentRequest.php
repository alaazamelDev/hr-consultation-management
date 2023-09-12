<?php


namespace App\Application\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class EditEmployeeDepartmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dep_id' => [
                'required',
                'integer',
                'exists:departments,dep_id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'dep_id.required' => 'معرّف القسم مطلوب',
            'dep_id.integer' => 'معرف القسم غير صالح',
            'dep_id.exists' => 'القسم غير موجود',
        ];
    }
}
