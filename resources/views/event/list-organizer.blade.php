@push('css')
    <link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Organizer event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @include('event.partials.list')
    </div>
</x-app-layout>
