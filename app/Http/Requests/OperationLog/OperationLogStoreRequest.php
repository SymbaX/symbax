<?php

namespace App\Http\Requests\OperationLog;

use Illuminate\Foundation\Http\FormRequest;

class OperationLogStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'message' => ['required', 'string'],
            'user_id' => ['nullable', 'string'],
        ];
    }
}
