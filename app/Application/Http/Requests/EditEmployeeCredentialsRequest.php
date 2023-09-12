<?php


namespace App\Application\Http\Requests;


use App\Domain\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditEmployeeCredentialsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'sometimes',
                'email',
                // check if the email is unique except for the current user
                Rule::unique('users', 'email')
                    ->ignore(Employee::query()
                        ->where('emp_id', $this->route('id'))
                        ->first()
                        ->user
                        ->user_id,'user_id')
            ],
            'username' => [
                'sometimes',
                // check if the username is unique except for the current user
                Rule::unique('users', 'username')
                    ->ignore(Employee::query()
                        ->where('emp_id', $this->route('id'))
                        ->first()
                        ->user
                        ->user_id,'user_id')
            ],
            'password' => ['sometimes', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'username.unique' => 'اسم المستخدم مستخدم من قبل',
            'password.min' => 'يجب أن تكون كلمة المرور على الأقل 8 أحرف',
        ];
    }

}
