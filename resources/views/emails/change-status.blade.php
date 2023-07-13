<x-mail::message>
# ステータスが変更されました

以下のボタンからステータスを確認してください

<x-mail::button :url="config('app.url') . '/all'">
    確認する
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
