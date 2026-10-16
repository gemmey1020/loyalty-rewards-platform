<?php

return [
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute must be a valid email address.',
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'max' => [
        'string' => 'The :attribute may not be greater than :max characters.',
    ],
    'confirmed' => 'The :attribute confirmation does not match.',
    'regex' => 'The :attribute format is invalid.',
    'date' => 'The :attribute is not a valid date.',
    'before' => 'The :attribute must be a date before :date.',
    'image' => 'The :attribute must be an image.',
    'mimes' => 'The :attribute must be a file of type: :values.',
    'unique' => 'The :attribute has already been taken.',
    'string' => 'The :attribute must be a string.',
    'numeric' => 'The :attribute must be a number.',
    'file' => 'The :attribute must be a file.',

    // Optional: customize the names of the fields
    'attributes' => [
        'name' => 'name',
        'email' => 'email address',
        'phone' => 'phone number',
        'password' => 'password',
        'password_confirmation' => 'password confirmation',
        'date' => 'date of birth',
        'image' => 'image',
    ],
];
