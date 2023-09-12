<?php


namespace App\Application\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerLoginRequest extends FormRequest
{

    // validation rules
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'min:8',
            ],
        ];
    }

    // validation messages
    public function messages(): array
    {
        return [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.string' => 'البريد الإلكتروني غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف',
        ];
    }

}
