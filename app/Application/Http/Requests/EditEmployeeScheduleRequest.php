<?php


namespace App\Application\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class EditEmployeeScheduleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'schedule_id' => ['required', 'integer', 'exists:schedules,schedule_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'schedule_id.required' => 'يجب تحديد جدول الدوام',
            'schedule_id.integer' => 'يجب أن يكون رقم جدول الدوام صحيحاً',
            'schedule_id.exists' => 'جدول الدوام غير موجود',
        ];
    }
}
