<x-mail::message>
# {{ $event_name }}
## 🔔 {{ $send_name }} さんからメンションされました 🔔

以下のボタンから内容を確認してください

<x-mail::button :url="$buttonUrl">
    確認する
</x-mail::button>

<br>
{{ config('app.name') }}
</x-mail::message>
