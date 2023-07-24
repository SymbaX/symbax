<div class="item__header clearfix">
    <span class="item__user-icon">
        <img src="{{ !empty($path) ? Storage::url($path) : asset('img/default-user.png') }}" alt=""
            class="w-16 h-16 rounded-full object-cover border-none bg-gray-200">
    </span>
    <span class="item__user-name">
        <strong>{{ $name }}</strong>
    </span>
    <span class="item__id">
        {{ $id }}
    </span>
</div>
