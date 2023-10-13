@push('css')
    <link rel="stylesheet" href="{{ asset('css/admin-table.css') }}">
@endpush
@push('script')
    <script src="{{ asset('script/user-edit-modal.js') }}" defer></script>
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event lists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">

                    <table class="tb01">
                        <tr class="head">
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Organizer') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Deadline date') }}</th>

                        </tr>
                        @foreach ($events as $event)
                            <tr>
                                <td data-label="{{ __('ID') }}">{{ $event->id }}</td>
                                <td data-label="{{ __('Name') }}">{{ $event->name }}</td>
                                <td data-label="{{ __('Organizer') }}">{{ $event->organizer_id }}</td>
                                <td data-label="{{ __('Date') }}">
                                    {{ Carbon\Carbon::parse($event->date)->format('Y/m/d') }}
                                <td data-label="{{ __('Deadline date') }}">
                                    {{ Carbon\Carbon::parse($event->deadline_date)->format('Y/m/d') }}
                            </tr>
                        @endforeach

                    </table>
                    {{ $events->links('vendor.pagination.tailwind02') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
