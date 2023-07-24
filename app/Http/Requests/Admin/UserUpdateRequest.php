<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        // 認証や権限のチェックなどはここで行うことができます。
        return true;
    }

    public function rules()
    {
        return [
            'login_id' => [
                'required',
                'string',
                'min:4',
                'max:15',
                Rule::unique('users')->ignore($this->user),
                'regex:/^(?=.*[a-z0-9])[a-z0-9_]+$/',
            ],
            'name' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email'],
            'college' => ['required', 'exists:colleges,id'],
            'department' => [
                'required', 'exists:departments,id', Rule::exists('departments', 'id')->where(function ($query) {
                    $query->where('college_id', $this->input('college'));
                })
            ],
            'role' => 'required',
        ];
    }
}
