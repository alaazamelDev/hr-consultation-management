<?php


namespace App\Application\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftRequest extends FormRequest
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
            'description' => ['sometimes', 'string'],
            'new_time_in' => ['sometimes', 'date_format:H:i:s'],
            'new_time_out' => ['sometimes', 'date_format:H:i:s'],
            'start_date' => ['sometimes', 'date_format:Y-m-d'],
            'duration' => ['sometimes', 'integer', 'min:1'],
            'remaining_days' => ['sometimes', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'description.string' => 'يجب إدخال سبب الطلب بشكل صحيح',
            'new_time_in.date_format' => 'يجب إدخال وقت الحضور الجديد بشكل صحيح',
            'new_time_out.date_format' => 'يجب إدخال وقت الإنصراف الجديد بشكل صحيح',
            'start_date.date_format' => 'يجب إدخال تاريخ بداية الطلب بشكل صحيح',
            'duration.integer' => 'يجب إدخال مدة الطلب بشكل صحيح',
            'duration.min' => 'يجب أن تكون مدة الطلب أكبر من الصفر',
            'remaining_days.integer' => 'يجب إدخال عدد الأيام المتبقية بشكل صحيح',
            'remaining_days.min' => 'يجب أن يكون عدد الأيام المتبقية أكبر من الصفر',
        ];
    }

}
