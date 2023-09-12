<?php

namespace App\Application\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddCustomerRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],
            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],
            'job' => [
                'required',
                'string',
                'max:100',
            ],
            'birth_date' => [
                'required',
                'date',
                'before:today',
            ],
            'phone' => [
                'string',
                'min:7',
                'max:15',
            ],
            'phone_number' => [
                'required',
                'string',
                'min:10',
                'max:15',
                Rule::unique('customers', 'phone_number') //TODO ->whereNull('deleted_at')],
            ],
            'martial_status' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'num_of_children' => [
                'required',
                'integer',
                'min:0',
                'max:20',
            ],
            'national_number' => [
                'string',
                'size:11',
                Rule::unique('customers', 'national_number') //TODO ->whereNull('deleted_at')],
            ],
            'profile_picture' => [
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
            ],
            'education_level_id' => [
                'required',
                'integer',
                'exists:education_levels,education_level_id',
            ],
            'email' => [
                'required', //TODO check it
                'email',
                'unique:customers,email',
                Rule::unique('customers', 'email'), //TODO ->whereNull('deleted_at')],
            ],
//            'username' => [
//                'required',
//                'string',
//                'min:5',
//                'max:50',
//                'unique:customers,username',
//            ],
//            'password' => [
//                'required',
//                'confirmed',
//                'min:8',
//            ],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'حقل الاسم الأول مطلوب.',
            'first_name.string' => 'يجب أن يكون الاسم الأول نصًا.',
            'first_name.min' => 'يجب أن يحتوي الاسم الأول على الأقل على حرفين.',
            'first_name.max' => 'يجب ألا يتجاوز الاسم الأول خمسين حرفًا.',
            'last_name.required' => 'حقل الاسم الأخير مطلوب.',
            'last_name.string' => 'يجب أن يكون الاسم الأخير نصًا.',
            'last_name.min' => 'يجب أن يحتوي الاسم الأخير على الأقل على حرفين.',
            'last_name.max' => 'يجب ألا يتجاوز الاسم الأخير خمسين حرفًا.',
            'job.required' => 'حقل الوظيفة مطلوب.',
            'job.string' => 'يجب أن تكون الوظيفة نصًا.',
            'job.min' => 'يجب أن يحتوي حقل الوظيفة على الأقل على خمس أحرف.',
            'job.max' => 'يجب ألا يتجاوز حقل الوظيفة مئة حرفًا.',
            'birth_date.required' => 'حقل تاريخ الميلاد مطلوب.',
            'birth_date.date' => 'يجب أن يكون تاريخ الميلاد صحيحًا.',
            'birth_date.before' => 'يجب أن يكون تاريخ الميلاد قبل اليوم.',
            'phone.string' => 'يجب أن يكون الهاتف نصًا.',
            'phone.min' => 'يجب أن يحتوي الهاتف على الأقل على سبع حرفاً.',
            'phone.max' => 'يجب ألا يتجاوز الهاتف خمسة عشر رقماً.',
            'phone_number.required' => 'حقل رقم الهاتف مطلوب.',
            'phone_number.string' => 'يجب أن يكون رقم الهاتف نصًا.',
            'phone_number.min' => 'يجب أن يحتوي رقم الهاتف على الأقل على عشر أرقام.',
            'phone_number.max' => 'يجب ألا يتجاوز رقم الهاتف خمسة عشر حرفًا.',
            'martial_status.required' => 'حقل الحالة الاجتماعية مطلوب.',
            'num_of_children.required' => 'حقل عدد الأطفال مطلوب.',
            'num_of_children.integer' => 'يجب أن يكون عدد الأطفال رقمًا صحيحًا.',
            'num_of_children.min' => 'يجب أن يكون عدد الأطفال على الأقل :min.',
            'num_of_children.max' => 'يجب ألا يتجاوز عدد الأطفال :max.',
            'national_number.required' => 'حقل الرقم الوطني مطلوب.',
            'national_number.string' => 'يجب أن يكون الرقم الوطني نصًا.',
            'national_number.min' => 'يجب أن يحتوي الرقم الوطني على أحد عشر رقماً.',
            'national_number.max' => 'يجب أن يحتوي الرقم الوطني على أحد عشر رقماً.',
            'education_level_id.required' => 'حقل المستوى التعليمي مطلوب.',
            'education_level_id.exists' => 'المستوى التعليمي المحدد غير صالح.',
            'email.email' => 'البريد الإلكتروني غير صحيح.',
//            'username.required' => 'حقل اسم المستخدم مطلوب.',
//            'username.string' => 'يجب أن يكون اسم المستخدم نصًا.',
//            'username.min' => 'يجب أن يحتوي اسم المستخدم على الأقل على خمس أحرف.',
//            'username.max' => 'يجب ألا يتجاوز اسم المستخدم خمسين حرفًا.',
//            'username.unique' => 'اسم المستخدم موجود مسبقاُ.',
//            'password.required' => 'حقل كلمة المرور مطلوب.',
//            'password.min' => 'يجب أن تكون كلمة المرور على الأقل ثمان أحرف.',
        ];
    }

}
