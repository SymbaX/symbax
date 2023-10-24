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
                            <th>{{ __('Event Status') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Deadline date') }}</th>
                            <th>{{ __('Delete') }}</th>
                        </tr>
                        @foreach ($events as $event)
                            <tr>
                                <td data-label="{{ __('ID') }}">{{ $event->id }}</td>
                                <td data-label="{{ __('Name') }}">
                                    @if ($event->is_deleted)
                                        {{ $event->name }}
                                    @else
                                        <a href="{{ route('event.show', $event->id) }}"
                                            class="text-blue-500 hover:text-blue-700">
                                            {{ $event->name }}
                                        </a>
                                    @endif

                                </td>
                                <td data-label="{{ __('Organizer') }}">{{ $event->organizer_id }}</td>
                                <td data-label="{{ __('Event Status') }}">
                                    @lang('status.' . $event->getStatus())
                                </td>
                                <td data-label="{{ __('Date') }}">
                                    {{ Carbon\Carbon::parse($event->date)->format('Y/m/d') }}
                                <td data-label="{{ __('Deadline date') }}">
                                    {{ Carbon\Carbon::parse($event->deadline_date)->format('Y/m/d') }}
                                <td data-label="{{ __('Delete') }}">
                                    @if ($event->is_deleted)
                                        <x-primary-button disabled>
                                            {{ __('Delete') }}
                                        </x-primary-button>
                                    @else
                                        <form action="{{ route('admin.event.delete', ['event' => $event->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <x-primary-button>
                                                {{ __('Delete') }}
                                            </x-primary-button>
                                        </form>
                                    @endif

                                </td>

                            </tr>
                        @endforeach

                    </table>
                    {{ $events->links('vendor.pagination.tailwind02') }}
                </div>
            </div>
            <x-status-modal />

        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-danger').forEach(function(button) {
            button.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this event?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
