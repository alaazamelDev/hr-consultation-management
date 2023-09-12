<?php


namespace App\Application\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required_without:username',
                'email',
                'nullable',
                Rule::exists('users', 'email')
                    ->whereIn('user_type_id', [1,2])
            ],
            'username' => [
                'required_without:email',
                'nullable',
                Rule::exists('users', 'username')
                    ->whereIn('user_type_id', [1,2])
            ],
            'password' => [
                'required',
                'min:8',
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'email.required_without' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.exists' => 'البريد الإلكتروني غير موجود',
            'username.required_without' => 'اسم المستخدم مطلوب',
            'username.exists' => 'اسم المستخدم غير موجود',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف',
        ];
    }
}
