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
            'detail'            => ['required', 'string'],
            'user_id'           => ['nullable', 'string'],
            'target_event_id'   => ['nullable', 'string'],
            'target_user_id'    => ['nullable', 'string'],
            'target_topic_id'   => ['nullable', 'string'],
            'action	'           => ['nullable', 'string'],
        ];
    }
}
