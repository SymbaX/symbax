<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

/**
 * プロフィール削除リクエストクラス
 */
class ProfileDeleteRequest extends FormRequest
{
    /**
     * リクエストの認証を行います。
     *
     * @return bool 認証結果（常にtrueとしています）
     */
    public function authorize()
    {
        return true;
    }

    /**
     * リクエストのバリデーションルールを定義します。
     *
     * @return array バリデーションルールの配列
     */
    public function rules()
    {
        return [
            'password' => ['required', 'current_password'],
        ];
    }
}
