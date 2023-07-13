<x-mail::message>
# {{ $event_name }}
## イベントに参加リクエストがありました

以下のボタンから参加リクエストを確認してください

<x-mail::button :url="$buttonUrl">
    確認する
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
