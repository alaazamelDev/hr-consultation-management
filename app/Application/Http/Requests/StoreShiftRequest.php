<?php


namespace App\Application\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
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
            //  'emp_id' => ['required', 'integer', 'exists:employees,emp_id'],
            'user_id' => ['required', 'integer', 'exists:users,user_id'],
            'description' => ['required', 'string'],
            'new_time_in' => ['required', 'date_format:H:i:s'],
            'new_time_out' => ['required', 'date_format:H:i:s'],
            'start_date' => ['required', 'date'],
            'duration' => ['required', 'integer', 'min:1'],
            'remaining_days' => ['sometimes', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'يجب إدخال رقم الموظف',
            'user_id.integer' => 'يجب إدخال رقم الموظف بشكل صحيح',
            'user_id.exists' => 'يجب إدخال رقم موظف موجود',
            'description.required' => 'يجب إدخال سبب الطلب',
            'description.string' => 'يجب إدخال سبب الطلب بشكل صحيح',
            'new_time_in.required' => 'يجب إدخال وقت الحضور الجديد',
            'new_time_in.date_format' => 'يجب إدخال وقت الحضور الجديد بشكل صحيح',
            'new_time_out.required' => 'يجب إدخال وقت الإنصراف الجديد',
            'new_time_out.date_format' => 'يجب إدخال وقت الإنصراف الجديد بشكل صحيح',
            'start_date.required' => 'يجب إدخال تاريخ بداية الطلب',
            'start_date.date' => 'يجب إدخال تاريخ بداية الطلب بشكل صحيح',
            'duration.required' => 'يجب إدخال مدة الطلب',
            'duration.integer' => 'يجب إدخال مدة الطلب بشكل صحيح',
            'duration.min' => 'يجب أن تكون مدة الطلب أكبر من الصفر',
            'remaining_days.integer' => 'يجب إدخال عدد الأيام المتبقية بشكل صحيح',
            'remaining_days.min' => 'يجب أن يكون عدد الأيام المتبقية أكبر من الصفر',
        ];
    }
}
