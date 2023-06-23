<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event list') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if ($events->isEmpty())
                    <p>No events found.</p>
                    @else
                    <ul>
                        @foreach ($events as $event)
                        <li><a href="details/{{$event->id}}">{{ $event->name }}</a></li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                <br />
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-app-layout>