<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email_or_phone' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $value = request('email_or_phone');

            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                if (! User::where('email', $value)->exists()) {
                    $validator->errors()->add('email_or_phone', __('auth.email_not_found'));
                }
            } elseif (preg_match('/^(?:\+965|00965)?[569]\d{7}$/', $value)) {
                if (! User::where('phone', $value)->exists()) {
                    $validator->errors()->add('email_or_phone', __('auth.phone_not_found'));
                }
            } else {
                $validator->errors()->add('email_or_phone', __('auth.invalid_email_or_phone'));
            }
        });
    }

    public function messages(): array
    {
        return [
            'email_or_phone.required' => __('auth.email_or_phone_required'),
            'email_or_phone.max' => __('auth.email_or_phone_max'),
            'password.required' => __('auth.password_required'),
        ];
    }
}
