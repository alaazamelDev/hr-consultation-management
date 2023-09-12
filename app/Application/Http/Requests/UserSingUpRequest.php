<?php

namespace App\Application\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserSingUpRequest extends FormRequest
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
                'max:50',
            ],
            'last_name' => [
                'required',
                'string',
                'max:50',
            ],
            'birth_date' => [
                'required',
                'date',
                'before:today',
            ],
            'phone_number' => [
                'required',
                'string',
                'min:10',
                'max:15',
//                Rule::unique('customers', 'phone_number') //TODO ->whereNull('deleted_at')],
            ],
            'phone' => [
                'string',
                'min:7',
                'max:15',
            ],
            'education_level_id' => [
                'required',
                'integer',
                'exists:education_levels,education_level_id',
            ],
            'job' => [
                'required',
                'string',
                'max:50',
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
            'profile_picture' => [
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:4096',
            ],
            'email' => [
                'required', //TODO check it
                'email',
                'unique:customers,email',
            ],
            'username' => [
                'required',
                'string',
                'min:5',
                'max:50',
                'unique:customers,username',
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'الاسم الاول مطلوب',
            'first_name.string' => 'الاسم الاول يجب ان يكون نص',
            'first_name.max' => 'الاسم الاول يجب ان لا يزيد عن 50 حرف',
            'last_name.required' => 'الاسم الاخير مطلوب',
            'last_name.string' => 'الاسم الاخير يجب ان يكون نص',
            'last_name.max' => 'الاسم الاخير يجب ان لا يزيد عن 50 حرف',
            'birth_date.required' => 'تاريخ الميلاد مطلوب',
            'birth_date.date' => 'تاريخ الميلاد يجب ان يكون تاريخ',
            'birth_date.before' => 'تاريخ الميلاد يجب ان يكون قبل اليوم',
            'phone_number.required' => 'رقم الهاتف مطلوب',
            'phone_number.string' => 'رقم الهاتف يجب ان يكون نص',
            'phone_number.min' => 'رقم الهاتف يجب ان لا يقل عن 10 ارقام',
            'phone_number.max' => 'رقم الهاتف يجب ان لا يزيد عن 15 رقم',
            'phone_number.unique' => 'رقم الهاتف موجود مسبقا',
            'phone.string' => 'رقم الهاتف يجب ان يكون نص',
            'phone.min' => 'رقم الهاتف يجب ان لا يقل عن 7 ارقام',
            'phone.max' => 'رقم الهاتف يجب ان لا يزيد عن 15 رقم',
            'education_level_id.required' => 'المستوى التعليمي مطلوب',
            'education_level_id.integer' => 'المستوى التعليمي يجب ان يكون رقم',
            'education_level_id.exists' => 'المستوى التعليمي غير موجود',
            'job.required' => 'الوظيفة مطلوبة',
            'job.string' => 'الوظيفة يجب ان تكون نص',
            'job.min' => 'الوظيفة يجب ان لا تقل عن 5 حروف',
            'job.max' => 'الوظيفة يجب ان لا تزيد عن 50 حرف',
            'martial_status.required' => 'الحالة الاجتماعية مطلوبة',
            'martial_status.integer' => 'الحالة الاجتماعية يجب ان تكون رقم',
            'martial_status.min' => 'الحالة الاجتماعية يجب ان لا تقل عن 1',
            'martial_status.max' => 'الحالة الاجتماعية يجب ان لا تزيد عن 5',
            'num_of_children.required' => 'عدد الاطفال مطلوب',
            'num_of_children.integer' => 'عدد الاطفال يجب ان يكون رقم',
            'num_of_children.min' => 'عدد الاطفال يجب ان لا يقل عن 0',
            'num_of_children.max' => 'عدد الاطفال يجب ان لا يزيد عن 20',
            'profile_picture.image' => 'الصورة يجب ان تكون صورة',
            'profile_picture.mimes' => 'الصورة يجب ان تكون من نوع jpeg,png,jpg,gif,svg',
            'profile_picture.max' => 'الصورة يجب ان لا تزيد عن 4096 كيلوبايت',
            'email.required' => 'البريد الالكتروني مطلوب',
            'email.email' => 'البريد الالكتروني يجب ان يكون بريد الكتروني',
            'email.unique' => 'البريد الالكتروني موجود مسبقا',
            'username.required' => 'اسم المستخدم مطلوب',
            'username.string' => 'اسم المستخدم يجب ان يكون نص',
            'username.min' => 'اسم المستخدم يجب ان لا يقل عن 5 حروف',
            'username.max' => 'اسم المستخدم يجب ان لا يزيد عن 50 حرف',
            'username.unique' => 'اسم المستخدم موجود مسبقا',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'password.min' => 'كلمة المرور يجب ان لا تقل عن 8 حروف',
        ];
    }

}
