<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if ($event)
                    <div class="max-w-xl">
                        <h1>{{ $event->name }}</h1>
                        <p>{{ $event->details }}</p>
                        <p>{{ $event->category }}</p>
                        <p>{{ $event->tag }}</p>
                        <p>{{ $event->conditions_of_participation }}</p>
                        <p>{{ $event->external_links }}</p>
                        <p>{{ $event->datetime }}</p>
                        <p>{{ $event->place }}</p>
                        <p>{{ $event->number_of_people }}</p>
                    </div>
                    @else
                    <p>Event not found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>