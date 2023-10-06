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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                @include('event.partials.list')
            </div>
        </div>
    </div>
    <a href="{{ route('event.create') }}" class="create_fab">+</a>
</x-app-layout>
