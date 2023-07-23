<?php

namespace App\Http\Requests\Profile;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * プロフィール更新リクエストフォームリクエストクラス
 *
 * このクラスは、プロフィール更新リクエストに関するフォームリクエスト処理を行います。
 */
class ProfileUpdateRequest extends FormRequest
{
    /**
     * リクエストに適用されるバリデーションルールを取得します。
     *
     * @return array バリデーションルールの配列
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:20'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'picture' => ['file', 'mimes:gif,png,jpg,webp', 'max:3072'],
        ];
    }
}
