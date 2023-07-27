<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SendMailRequest extends FormRequest
{
    public function authorize()
    {
        return true;  // ユーザの権限によるアクセス制限がある場合はここでチェック
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email', 'max:254'],
            'subject' => ['required', 'string', 'max:78'],
            'body' => ['required', 'string', 'max:2000'],
        ];
    }
}
