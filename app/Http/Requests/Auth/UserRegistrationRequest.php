<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRegistrationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255', Rule::unique('users'), 'regex:/^[^@]+@g\.neec\.ac\.jp$/'
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
            'college' => ['required', 'exists:colleges,id'],
            'department' => [
                'required', 'exists:departments,id', Rule::exists('departments', 'id')->where(function ($query) {
                    $query->where('college_id', $this->input('college'));
                }),
            ],
        ];
    }
}
