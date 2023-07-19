<x-mail::message>
# ⚠管理者によってあなたのメールアドレスが変更されました⚠<br>
{{ $name }}さん、こんにちは。<br>
<br>
{{ config('app.name') }}管理者によってあなたのメールアドレスは変更されました。<br>
<br>
新しいメールアドレス（{{ $email }}）宛に<br>
認証メールを送信しましたのでご確認ください。

<br>
{{ config('app.name') }}
</x-mail::message>
