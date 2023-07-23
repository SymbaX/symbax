<x-mail::message>
# {{ $event_name }}
## 参加リクエストが承認されました
以下のボタンからステータスを確認してください。

<x-mail::button :url="$buttonUrl">
    確認する
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
