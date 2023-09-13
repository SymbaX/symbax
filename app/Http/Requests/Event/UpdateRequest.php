<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // 認証に関するロジックを記述します。必要に応じて変更してください。
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => ['required', 'max:20'],
            'detail' => ['required', 'max:1000'],
            'category' => ['required', 'max:30'],
            'tag' => ['required', 'max:30'],
            'participation_condition' => ['required', 'max:100'],
            'external_link' => ['required', 'max:255', 'url'],
            'place' => ['required', 'max:50'],
            'number_of_recruits' => ['required', 'integer', 'min:1'],
            'image_path_a' => ['nullable', 'max:5000', 'mimes:jpg,jpeg,png,gif'],
            'image_path_b' => ['nullable', 'max:5000', 'mimes:jpg,jpeg,png,gif'],
            'image_path_c' => ['nullable', 'max:5000', 'mimes:jpg,jpeg,png,gif'],
            'image_path_d' => ['nullable', 'max:5000', 'mimes:jpg,jpeg,png,gif'],
            'image_path_e' => ['nullable', 'max:5000', 'mimes:jpg,jpeg,png,gif'],
            'img_delete_b' => ['nullable'],
            'img_delete_c' => ['nullable'],
            'img_delete_d' => ['nullable'],
            'img_delete_e' => ['nullable'],
        ];

        return $rules;
    }
}
