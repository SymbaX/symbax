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
            'name' => ['required', 'string', 'max:20'],
            'login_id' => [
                'required', 'string',
                'min:4',
                'max:15',
                Rule::unique('users'),
                'regex:/^(?=.*[a-z0-9])[a-z0-9_]+$/',
                'not_in:all'
            ],
            'email' => [
                'required', 'string', 'email', 'max:255', Rule::unique('users'), 'regex:/^[a-zA-Z0-9][^@]+@g\.neec\.ac\.jp$/', 'regex:/^(?!.*[+]).*$/'
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
            'college' => ['required', 'exists:colleges,id'],
            'department' => [
                'required', 'exists:departments,id', Rule::exists('departments', 'id')->where(function ($query) {
                    $query->where('college_id', $this->input('college'));
                }),
            ],
            'terms' => 'required|accepted',
            'privacy_policy' => 'required|accepted',
        ];
    }
}
