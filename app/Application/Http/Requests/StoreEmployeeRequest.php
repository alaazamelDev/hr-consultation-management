<?php


namespace App\Application\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property mixed user
 */
class StoreEmployeeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users,email', Rule::unique('users')->ignore($this->user)],
            'username' => ['required', 'string', 'min:3', 'max:50', 'unique:users,username', Rule::unique('users')->ignore($this->user)],
            'password' => ['required', 'string', 'min:8', 'max:50'],

            // employee entity fields second.
            'job_app_id' => [
                'required',
                'integer',

                // check that job_app status is accepted.
                Rule::exists('job_applications', 'job_app_id')
                    ->where('app_status_id', 2),

                // check that job_app is not assigned to another employee.
                Rule::unique('employees', 'job_app_id')->ignore($this->user)
            ],
            'schedule_id' => ['required', 'integer', 'exists:schedules,schedule_id'],

            // staffing entity fields third.
            'job_title_id' => [
                'required',
                'integer',
                'exists:job_titles,job_title_id'
            ],

            // additional permissions ids list.
            'additional_permissions' => ['nullable', 'array'],
            'additional_permissions.*' => [
                'required',
                'integer',
                'exists:permissions,perm_id'
            ],

            // excluded permissions ids list. (should not be found in additional permissions list)
            'excluded_permissions' => ['nullable', 'array'],
            'excluded_permissions.*' => [
                'required',
                'integer',
                Rule::notIn($this->additional_permissions),

                // check that excluded permissions are in job_title permissions.
                Rule::exists('job_title_permissions', 'perm_id')
                    ->where('job_title_id', $this->job_title_id)
            ],

            'start_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل.',
            'email.email' => 'البريد الإلكتروني غير صالح.',

            'username.required' => 'اسم المستخدم مطلوب.',
            'username.unique' => 'اسم المستخدم مستخدم من قبل.',
            'username.min' => 'اسم المستخدم يجب أن يكون على الأقل 3 أحرف.',

            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف.',

            'job_app_id.required' => 'معرف طلب التوظيف مطلوب.',
            'job_app_id.integer' => 'معرف طلب التوظيف يجب أن يكون رقماً صحيحاً.',
            'job_app_id.exists' => 'معرف طلب التوظيف غير موجود.',
            'job_app_id.unique' => 'معرف طلب التوظيف مستخدم من قبل.',

            'schedule_id.required' => 'معرف جدول الدوام مطلوب.',
            'schedule_id.integer' => 'معرف جدول الدوام يجب أن يكون رقماً صحيحاً.',
            'schedule_id.exists' => 'معرف جدول الدوام غير موجود.',

            'job_title_id.required' => 'معرف المسمّى الوظيفي مطلوب.',
            'job_title_id.integer' => 'معرف المسمّى الوظيفي يجب أن يكون رقماً صحيحاً.',
            'job_title_id.exists' => 'معرف المسمّى الوظيفي غير موجود.',

            'additional_permissions.array' => 'قائمة الصلاحيات الإضافية يجب أن تكون مصفوفة.',
            'additional_permissions.*.required' => 'معرف الصلاحية الإضافية مطلوب.',
            'additional_permissions.*.integer' => 'معرف الصلاحية الإضافية يجب أن يكون رقماً صحيحاً.',
            'additional_permissions.*.exists' => 'معرف الصلاحية الإضافية غير موجود.',

            'excluded_permissions.array' => 'قائمة الصلاحيات المستثناة يجب أن تكون مصفوفة.',
            'excluded_permissions.*.required' => 'معرف الصلاحية المستثناة مطلوب.',
            'excluded_permissions.*.integer' => 'معرف الصلاحية المستثناة يجب أن يكون رقماً صحيحاً.',
            'excluded_permissions.*.not_in' => 'لا يمكن أن تكون الصلاحية المستثناة موجودة في قائمة الصلاحيات الإضافية.',
            'excluded_permissions.*.exists' => 'الصلاحية المستثناة غير موجودة في صلاحيات المسمّى الوظيفي.',

            'start_date.required' => 'تاريخ بدء العمل مطلوب.',
            'start_date.date' => 'تاريخ بدء العمل غير صالح.',
        ];
    }


}
