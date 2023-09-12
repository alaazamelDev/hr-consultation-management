<?php


namespace App\Application\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StoreVacationRequest extends FormRequest
{
    // TODO: Add validation for the user_id to be an employee
//    public function authorize(): bool
//    {
//        // check that the user is an employee
//        return auth()->user()->isEmployee();
//    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,user_id'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date', 'after:yesterday'],
            'duration' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'يجب تحديد معرف الموظف',
            'user_id.integer' => 'معرف الموظف يجب أن يكون رقماً صحيحاً',
            'user_id.exists' => 'معرف الموظف غير موجود',
            'description.required' => 'يجب تحديد سبب الطلب',
            'description.string' => 'سبب الطلب يجب أن يكون نصاً',
            'start_date.required' => 'يجب تحديد تاريخ بداية الإجازة',
            'start_date.date' => 'تاريخ بداية الإجازة يجب أن يكون تاريخاً',
            'start_date.after' => 'تاريخ بداية الإجازة يجب أن يكون بعد تاريخ اليوم',
            'duration.required' => 'يجب تحديد مدة الإجازة',
            'duration.integer' => 'مدة الإجازة يجب أن تكون رقماً صحيحاً',
            'duration.min' => 'مدة الإجازة يجب أن تكون على الأقل يوماً واحداً',
        ];
    }
}
