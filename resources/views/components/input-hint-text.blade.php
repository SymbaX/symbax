@props(['value'])

<p style="text-align: right;font-size:12px;">
    {{ $value ?? $slot }}
</p>
