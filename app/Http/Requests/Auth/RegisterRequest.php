<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
            'phone' => ['required', 'string', 'max:15', 'unique:users,phone'],
            'date' => ['nullable', 'date', 'before:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('fields.name')]),
            'name.string' => __('validation.string', ['attribute' => __('fields.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('fields.name'), 'max' => 255]),

            'email.required' => __('validation.required', ['attribute' => __('fields.email')]),
            'email.string' => __('validation.string', ['attribute' => __('fields.email')]),
            'email.email' => __('validation.email', ['attribute' => __('fields.email')]),
            'email.max' => __('validation.max.string', ['attribute' => __('fields.email'), 'max' => 255]),
            'email.unique' => __('validation.unique', ['attribute' => __('fields.email')]),

            'password.required' => __('validation.required', ['attribute' => __('fields.password')]),
            'password.string' => __('validation.string', ['attribute' => __('fields.password')]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('fields.password')]),
            'password.min' => __('validation.min.string', ['attribute' => __('fields.password'), 'min' => 8]),

            'phone.required' => __('validation.required', ['attribute' => __('fields.phone')]),
            'phone.string' => __('validation.string', ['attribute' => __('fields.phone')]),
            'phone.max' => __('validation.max.string', ['attribute' => __('fields.phone'), 'max' => 15]),
            'phone.unique' => __('validation.unique', ['attribute' => __('fields.phone')]),

            'date.date' => __('validation.date', ['attribute' => __('fields.date')]),
            'date.before' => __('validation.before', ['attribute' => __('fields.date'), 'date' => 'today']),
        ];
    }
}
