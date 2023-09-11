<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'date' => ['required', 'date', 'after_or_equal:today'],
            'deadline_date' => ['required', 'date', 'after_or_equal:today'],
            'place' => ['required', 'max:50'],
            'number_of_recruits' => ['required', 'integer', 'min:1'],
            'image_path_a' => ['required', 'max:5000', 'mimes:jpg,jpeg,png,gif'],
            'image_path_b' => ['max:5000', 'mimes:jpg,jpeg,png,gif'],
            'image_path_c' => ['max:5000', 'mimes:jpg,jpeg,png,gif'],
            'image_path_d' => ['max:5000', 'mimes:jpg,jpeg,png,gif'],
            'image_path_e' => ['max:5000', 'mimes:jpg,jpeg,png,gif'],
        ];

        return $rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $deadline_date = $this->get('deadline_date');
            $date = $this->get('date');

            if ($deadline_date !== null && $date !== null) {
                $deadline_date = \Carbon\Carbon::createFromFormat('Y-m-d', $deadline_date);
                $date = \Carbon\Carbon::createFromFormat('Y-m-d', $date);

                if ($deadline_date->gte($date)) {
                    $validator->errors()->add('deadline_date', trans('validation.custom.deadline_date.before_event_date'));
                }
            }
        });
    }
}
