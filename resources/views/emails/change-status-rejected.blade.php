<x-mail::message>
# 参加リクエストが却下されました
以下のボタンからステータスを確認してください。

<x-mail::button :url="$buttonUrl">
    確認する
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
