<?php


namespace App\Application\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class EditEmploymentStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'emp_status_id' => ['required', 'exists:employment_statuses,emp_status_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'emp_status_id.required' => 'يجب تحديد الوضع الوظيفي',
            'emp_status_id.exists' => 'الوضع الوظيفي غير موجود',
        ];
    }

}
